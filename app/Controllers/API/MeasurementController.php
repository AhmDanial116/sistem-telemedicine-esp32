<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\DeviceModel;
use App\Models\NotificationModel;
use App\Models\MeasurementModel;
use App\Models\MeasurementRequestModel;
use App\Models\DoctorAssignmentModel;
use App\Services\TelegramService;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class MeasurementController extends BaseController
{
    protected DeviceModel $deviceModel;

    protected MeasurementModel $measurementModel;

    protected MeasurementRequestModel $requestModel;

    public function __construct()
    {
        $this->deviceModel = new DeviceModel();

        $this->measurementModel = new MeasurementModel();

        $this->requestModel = new MeasurementRequestModel();
    }

    private function apiResponse(
        bool $success,
        string $message,
        mixed $data = null,
        mixed $errors = null,
        int $statusCode = 200
    ): ResponseInterface {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON([
                'success' => $success,
                'message' => $message,
                'data' => $data,
                'errors' => $errors,
            ]);
    }


    /**
     * Membaca body JSON dari ESP32 secara toleran.
     *
     * Urutan:
     * 1. Parser bawaan CodeIgniter.
     * 2. Raw body setelah BOM/whitespace dibersihkan.
     * 3. Ambil objek JSON pertama yang seimbang jika ada byte tambahan.
     */
    private function parseJsonBody(): array
    {
        $rawBody = (string) $this->request->getBody();
        $frameworkError = null;

        try {
            $frameworkPayload =
                $this->request->getJSON(true);

            if (is_array($frameworkPayload)) {
                return [
                    'success' => true,
                    'payload' => $frameworkPayload,
                    'diagnostic' => null,
                ];
            }
        } catch (Throwable $exception) {
            $frameworkError = $exception->getMessage();
        }

        // Hilangkan UTF-8 BOM di awal, NUL/control byte di ujung,
        // lalu whitespace normal.
        $cleanBody = preg_replace(
            '/^\\xEF\\xBB\\xBF/',
            '',
            $rawBody
        );

        if (! is_string($cleanBody)) {
            $cleanBody = $rawBody;
        }

        $cleanBody = preg_replace(
            '/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F\\x7F]+$/',
            '',
            $cleanBody
        );

        if (! is_string($cleanBody)) {
            $cleanBody = $rawBody;
        }

        $cleanBody = trim($cleanBody);

        $candidates = [];

        if ($cleanBody !== '') {
            $candidates[] = $cleanBody;

            $jsonObject =
                $this->extractFirstJsonObject(
                    $cleanBody
                );

            if (
                $jsonObject !== null
                && $jsonObject !== $cleanBody
            ) {
                $candidates[] = $jsonObject;
            }
        }

        $lastJsonError = 'Body kosong.';

        foreach ($candidates as $candidate) {
            $decoded = json_decode(
                $candidate,
                true,
                512,
                JSON_BIGINT_AS_STRING
                    | JSON_INVALID_UTF8_SUBSTITUTE
            );

            if (
                json_last_error() === JSON_ERROR_NONE
                && is_array($decoded)
            ) {
                return [
                    'success' => true,
                    'payload' => $decoded,
                    'diagnostic' => null,
                ];
            }

            $lastJsonError = json_last_error_msg();
        }

        $rawLength = strlen($rawBody);
        $prefix = substr($rawBody, 0, 80);
        $suffix = $rawLength > 80
            ? substr($rawBody, -80)
            : $rawBody;

        return [
            'success' => false,
            'payload' => null,
            'diagnostic' => [
                'json_error' => $lastJsonError,
                'framework_error' => $frameworkError,
                'content_type' =>
                $this->request->getHeaderLine(
                    'Content-Type'
                ),
                'content_length_header' =>
                $this->request->getHeaderLine(
                    'Content-Length'
                ),
                'raw_length' => $rawLength,
                'raw_prefix_hex' => bin2hex($prefix),
                'raw_suffix_hex' => bin2hex($suffix),
            ],
        ];
    }

    /**
     * Mengambil objek JSON pertama yang lengkap dari sebuah string.
     * Berguna bila proxy/client menyisakan byte tambahan setelah JSON.
     */
    private function extractFirstJsonObject(
        string $text
    ): ?string {
        $start = strpos($text, '{');

        if ($start === false) {
            return null;
        }

        $depth = 0;
        $inString = false;
        $escaped = false;
        $length = strlen($text);

        for ($i = $start; $i < $length; $i++) {
            $char = $text[$i];

            if ($inString) {
                if ($escaped) {
                    $escaped = false;
                    continue;
                }

                if ($char === '\\\\') {
                    $escaped = true;
                    continue;
                }

                if ($char === '"') {
                    $inString = false;
                }

                continue;
            }

            if ($char === '"') {
                $inString = true;
                continue;
            }

            if ($char === '{') {
                $depth++;
                continue;
            }

            if ($char === '}') {
                $depth--;

                if ($depth === 0) {
                    return substr(
                        $text,
                        $start,
                        $i - $start + 1
                    );
                }
            }
        }

        return null;
    }

    /**
     * POST /api/v1/measurement/upload
     */
    public function upload(): ResponseInterface
    {
        try {
            $parsedBody =
                $this->parseJsonBody();

            if (! $parsedBody['success']) {
                return $this->apiResponse(
                    false,
                    'Body request JSON tidak dapat dibaca.',
                    null,
                    $parsedBody['diagnostic'],
                    400
                );
            }

            $payload = $parsedBody['payload'];

            $requestId =
                isset($payload['request_id'])
                ? (int) $payload['request_id']
                : 0;

            $deviceId =
                isset($payload['device_id'])
                ? trim((string) $payload['device_id'])
                : '';

            if ($requestId <= 0 || $deviceId === '') {
                return $this->apiResponse(
                    false,
                    'Request ID dan Device ID wajib diisi.',
                    null,
                    null,
                    422
                );
            }

            $normalizedDeviceId =
                $this->deviceModel
                ->normalizeDeviceId($deviceId);

            if (
                strlen($normalizedDeviceId) !== 12
                || ! ctype_alnum($normalizedDeviceId)
            ) {
                return $this->apiResponse(
                    false,
                    'Format Device ID tidak valid.',
                    null,
                    null,
                    422
                );
            }

            $device =
                $this->deviceModel
                ->findActiveByDeviceId(
                    $normalizedDeviceId
                );

            if ($device === null) {
                return $this->apiResponse(
                    false,
                    'Perangkat tidak ditemukan atau tidak aktif.',
                    null,
                    null,
                    404
                );
            }

            if (empty($device['patient_id'])) {
                return $this->apiResponse(
                    false,
                    'Perangkat belum terhubung ke pasien.',
                    null,
                    null,
                    409
                );
            }

            $request =
                $this->requestModel
                ->find($requestId);

            if ($request === null) {
                return $this->apiResponse(
                    false,
                    'Request pengukuran tidak ditemukan.',
                    null,
                    null,
                    404
                );
            }

            if (
                (int) $request['device_record_id']
                !== (int) $device['id']
                ||
                (int) $request['patient_id']
                !== (int) $device['patient_id']
            ) {
                return $this->apiResponse(
                    false,
                    'Request tidak sesuai dengan perangkat atau pasien.',
                    null,
                    null,
                    403
                );
            }

            if (
                in_array(
                    $request['status'],
                    [
                        'completed',
                        'failed',
                        'expired',
                        'cancelled',
                    ],
                    true
                )
            ) {
                return $this->apiResponse(
                    false,
                    'Request sudah berada pada status akhir.',
                    null,
                    null,
                    409
                );
            }

            $measurementType =
                (string) (
                    $request['measurement_type']
                    ?? 'both'
                );

            $systolic =
                isset($payload['systolic'])
                && is_numeric($payload['systolic'])
                ? (float) $payload['systolic']
                : null;

            $map =
                isset($payload['map'])
                && is_numeric($payload['map'])
                ? (float) $payload['map']
                : null;

            $diastolic =
                isset($payload['diastolic'])
                && is_numeric($payload['diastolic'])
                ? (float) $payload['diastolic']
                : null;

            $bpm =
                isset($payload['bpm'])
                && is_numeric($payload['bpm'])
                ? (int) round(
                    (float) $payload['bpm']
                )
                : null;

            $hasAnyBloodPressure =
                $systolic !== null
                || $map !== null
                || $diastolic !== null;

            $hasBloodPressure =
                $systolic !== null
                && $map !== null
                && $diastolic !== null;

            $hasHeartRate =
                $bpm !== null;

            if (
                $hasAnyBloodPressure
                && ! $hasBloodPressure
            ) {
                return $this->apiResponse(
                    false,
                    'Data tekanan darah harus lengkap.',
                    null,
                    [
                        'blood_pressure' =>
                        'SYS, MAP, dan DIA harus dikirim bersama.',
                    ],
                    422
                );
            }

            if (
                $measurementType === 'heart_rate'
                && ! $hasHeartRate
            ) {
                return $this->apiResponse(
                    false,
                    'Hasil denyut nadi belum tersedia.',
                    null,
                    null,
                    422
                );
            }

            if (
                $measurementType === 'blood_pressure'
                && ! $hasBloodPressure
            ) {
                return $this->apiResponse(
                    false,
                    'Hasil tekanan darah belum tersedia.',
                    null,
                    null,
                    422
                );
            }

            if (
                $measurementType === 'both'
                && ! $hasBloodPressure
                && ! $hasHeartRate
            ) {
                return $this->apiResponse(
                    false,
                    'Tidak ada hasil pengukuran valid yang dapat disimpan.',
                    null,
                    null,
                    422
                );
            }

            $result =
                $this->measurementModel
                ->saveMeasurement(
                    [
                        'patient_id' =>
                        (int) $device['patient_id'],

                        'device_record_id' =>
                        (int) $device['id'],

                        'systolic' => $systolic,
                        'map' => $map,
                        'diastolic' => $diastolic,
                        'bpm' => $bpm,

                        'beat_count' =>
                        isset($payload['beat_count'])
                            && is_numeric(
                                $payload['beat_count']
                            )
                            ? (int) $payload['beat_count']
                            : null,

                        'pressure_baseline_adc' =>
                        isset(
                            $payload['pressure_baseline_adc']
                        )
                            && is_numeric(
                                $payload['pressure_baseline_adc']
                            )
                            ? (int) $payload['pressure_baseline_adc']
                            : null,

                        'adc_max' =>
                        isset($payload['adc_max'])
                            && is_numeric(
                                $payload['adc_max']
                            )
                            ? (int) $payload['adc_max']
                            : null,

                        'adc_min' =>
                        isset($payload['adc_min'])
                            && is_numeric(
                                $payload['adc_min']
                            )
                            ? (int) $payload['adc_min']
                            : null,

                        'measured_at' =>
                        date('Y-m-d H:i:s'),
                    ],
                    $requestId
                );

            if (! $result['success']) {
                return $this->apiResponse(
                    false,
                    $result['message'],
                    null,
                    $result['errors']
                        ?? $result['error']
                        ?? null,
                    422
                );
            }

            // Hasil sudah berhasil tersimpan.
            // Request harus dipindahkan ke status akhir agar
            // dashboard pasien berhenti polling pada status proses.
            $this->requestModel->markCompleted(
                $requestId
            );

            $firmwareVersion =
                $this->request->getHeaderLine(
                    'X-Firmware-Version'
                );

            $this->deviceModel->updateLastSeen(
                $normalizedDeviceId,
                $this->request->getIPAddress(),
                $firmwareVersion !== ''
                    ? $firmwareVersion
                    : null
            );

            $measurement =
                $this->measurementModel
                ->find(
                    $result['measurement_id']
                );

            /*
             * Notifikasi hanya untuk parameter yang:
             * 1. memang berhasil/valid, dan
             * 2. berada pada kondisi warning.
             *
             * Parameter yang tidak dipilih, gagal, atau invalid
             * tidak dikirim sebagai notifikasi.
             */
            if (
                $measurement !== null
                && $result['quality_status'] === 'warning'
            ) {
                $patientId =
                    (int) $measurement['patient_id'];

                $db = db_connect();

                $patient = $db
                    ->table('patients')
                    ->select([
                        'id',
                        'patient_code',
                        'name',
                    ])
                    ->where('id', $patientId)
                    ->get()
                    ->getRowArray();

                $assignment = $db
                    ->table('doctor_assignments')
                    ->select('doctor_id')
                    ->where('patient_id', $patientId)
                    ->where('status', 'active')
                    ->orderBy('assigned_at', 'DESC')
                    ->get()
                    ->getRowArray();

                if (
                    $patient !== null
                    && $assignment !== null
                ) {
                    $warningParts = [];

                    if (
                        ($result['blood_pressure_valid'] ?? false)
                        && ($result['blood_pressure_warning'] ?? false)
                        && $measurement['systolic'] !== null
                        && $measurement['diastolic'] !== null
                    ) {
                        $warningParts[] = sprintf(
                            'Tekanan darah: %.2f/%.2f mmHg (MAP %.2f mmHg)',
                            (float) $measurement['systolic'],
                            (float) $measurement['diastolic'],
                            (float) $measurement['map']
                        );
                    }

                    if (
                        ($result['heart_rate_valid'] ?? false)
                        && ($result['heart_rate_warning'] ?? false)
                        && $measurement['bpm'] !== null
                    ) {
                        $warningParts[] = sprintf(
                            'Denyut nadi: %d BPM',
                            (int) $measurement['bpm']
                        );
                    }

                    /*
                     * Harus ada minimal satu parameter valid
                     * yang benar-benar warning.
                     */
                    if ($warningParts !== []) {
                        $notificationTitle =
                            'Hasil Pengukuran Perlu Perhatian';

                        $notificationMessage = sprintf(
                            '%s (%s) memiliki %s.',
                            (string) $patient['name'],
                            (string) $patient['patient_code'],
                            implode(
                                ' dan ',
                                array_map(
                                    static fn(string $part): string =>
                                    lcfirst($part),
                                    $warningParts
                                )
                            )
                        );

                        /* Notifikasi internal dashboard dokter. */
                        $notificationModel =
                            new NotificationModel();

                        $notificationModel
                            ->createNotification([
                                'recipient_role' => 'doctor',
                                'recipient_id' =>
                                (int) $assignment['doctor_id'],
                                'title' =>
                                $notificationTitle,
                                'message' =>
                                $notificationMessage,
                                'type' => 'warning',
                                'reference_type' =>
                                'measurement',
                                'reference_id' =>
                                (int) $measurement['id'],
                                'action_url' =>
                                '/doctor/patient/'
                                    . $patientId,
                            ]);

                        /* Push notification Telegram. */
                        $telegramLines = [
                            '⚠️ HASIL PENGUKURAN PERLU PERHATIAN',
                            '',
                            'Pasien: '
                                . (string) $patient['name'],
                            'Kode: '
                                . (string) $patient['patient_code'],
                            'Jenis pengukuran: '
                                . $measurementType,
                            '',
                        ];

                        foreach ($warningParts as $part) {
                            $telegramLines[] = '• ' . $part;
                        }

                        $telegramLines[] = '';
                        $telegramLines[] =
                            'Silakan tinjau hasil pada sistem Telemedicine.';

                        $telegramService =
                            new TelegramService();

                        $telegramService->sendMessage(
                            implode("\n", $telegramLines)
                        );
                    }
                }
            }

            return $this->apiResponse(
                true,
                'Hasil pengukuran berhasil disimpan.',
                [
                    'measurement_id' =>
                    (int) $result['measurement_id'],

                    'request_id' =>
                    $requestId,

                    'measurement_type' =>
                    $measurementType,

                    'quality_status' =>
                    $result['quality_status'],

                    'is_valid' =>
                    $result['is_valid'],

                    'failure_reason' =>
                    $result['failure_reason'],
                ],
                null,
                201
            );
        } catch (Throwable $exception) {
            return $this->apiResponse(
                false,
                'Terjadi kesalahan pada server.',
                null,
                ENVIRONMENT === 'development'
                    ? [
                        'exception' =>
                        $exception->getMessage(),
                    ]
                    : null,
                500
            );
        }
    }

    /**
     * GET /api/v1/measurement/latest/{device_id}
     */
    public function latest(
        string $deviceId
    ): ResponseInterface {
        $measurement =
            $this->measurementModel
            ->getLatestByDeviceId(
                $deviceId
            );

        if ($measurement === null) {
            return $this->apiResponse(
                false,
                'Data pengukuran belum tersedia.',
                null,
                null,
                404
            );
        }

        return $this->apiResponse(
            true,
            'Hasil pengukuran terbaru ditemukan.',
            [
                'measurement' => $measurement,
            ]
        );
    }
}
