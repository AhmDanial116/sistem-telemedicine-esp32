<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\DeviceModel;
use App\Models\NotificationModel;
use App\Models\MeasurementModel;
use App\Models\MeasurementRequestModel;
use App\Models\DoctorAssignmentModel;
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
     * POST /api/v1/measurement/upload
     */
    public function upload(): ResponseInterface
    {
        try {
            $payload = $this->request->getJSON(true);

            if (! is_array($payload)) {
                return $this->apiResponse(
                    false,
                    'Body request harus berupa JSON.',
                    null,
                    [
                        'body' => 'JSON tidak valid atau kosong.',
                    ],
                    400
                );
            }

            $requestId = isset($payload['request_id'])
                ? (int) $payload['request_id']
                : 0;

            $deviceId = isset($payload['device_id'])
                ? trim((string) $payload['device_id'])
                : '';

            $systolic = $payload['systolic']
                ?? $payload['sys']
                ?? null;

            $map = $payload['map'] ?? null;

            $diastolic = $payload['diastolic']
                ?? $payload['dia']
                ?? null;

            $bpm = $payload['bpm'] ?? null;

            $beatCount = $payload['beat_count'] ?? null;

            $baselineAdc = $payload['pressure_baseline_adc']
                ?? $payload['baseline_adc']
                ?? null;

            $adcMax = $payload['adc_max'] ?? null;

            $adcMin = $payload['adc_min'] ?? null;

            $errors = [];

            if ($requestId <= 0) {
                $errors['request_id'] =
                    'Request ID harus berupa angka positif.';
            }

            if ($deviceId === '') {
                $errors['device_id'] =
                    'Device ID wajib diisi.';
            }

            if (! is_numeric($systolic)) {
                $errors['systolic'] =
                    'Nilai sistolik wajib berupa angka.';
            }

            if (! is_numeric($map)) {
                $errors['map'] =
                    'Nilai MAP wajib berupa angka.';
            }

            if (! is_numeric($diastolic)) {
                $errors['diastolic'] =
                    'Nilai diastolik wajib berupa angka.';
            }

            if (! is_numeric($bpm)) {
                $errors['bpm'] =
                    'Nilai BPM wajib berupa angka.';
            }

            if ($errors !== []) {
                return $this->apiResponse(
                    false,
                    'Data hasil pengukuran tidak lengkap.',
                    null,
                    $errors,
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
                    [
                        'device_id' =>
                        'Device ID harus terdiri dari 12 karakter huruf atau angka.',
                    ],
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
                    [
                        'device_id' =>
                        $normalizedDeviceId,
                    ],
                    404
                );
            }

            if (empty($device['patient_id'])) {
                return $this->apiResponse(
                    false,
                    'Perangkat belum terhubung ke pasien.',
                    null,
                    [
                        'device_id' =>
                        $normalizedDeviceId,
                    ],
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
                    [
                        'request_id' => $requestId,
                    ],
                    404
                );
            }

            if (
                (int) $request['device_record_id']
                !== (int) $device['id']
            ) {
                return $this->apiResponse(
                    false,
                    'Request tidak sesuai dengan perangkat.',
                    null,
                    [
                        'request_id' => $requestId,
                        'device_id' => $normalizedDeviceId,
                    ],
                    403
                );
            }

            if (
                (int) $request['patient_id']
                !== (int) $device['patient_id']
            ) {
                return $this->apiResponse(
                    false,
                    'Request tidak sesuai dengan pasien perangkat.',
                    null,
                    [
                        'request_id' => $requestId,
                    ],
                    403
                );
            }

            if (
                in_array(
                    $request['status'],
                    ['completed', 'failed', 'expired', 'cancelled'],
                    true
                )
            ) {
                return $this->apiResponse(
                    false,
                    'Request sudah berada pada status akhir.',
                    [
                        'request_id' => $requestId,
                        'current_status' => $request['status'],
                    ],
                    null,
                    409
                );
            }

            $result =
                $this->measurementModel
                ->saveMeasurement(
                    [
                        'patient_id'
                        => (int) $device['patient_id'],

                        'device_record_id'
                        => (int) $device['id'],

                        'systolic'
                        => (float) $systolic,

                        'map'
                        => (float) $map,

                        'diastolic'
                        => (float) $diastolic,

                        'bpm'
                        => (int) round((float) $bpm),

                        'beat_count'
                        => $beatCount !== null
                            ? (int) $beatCount
                            : null,

                        'pressure_baseline_adc'
                        => $baselineAdc !== null
                            ? (int) $baselineAdc
                            : null,

                        'adc_max'
                        => $adcMax !== null
                            ? (int) $adcMax
                            : null,

                        'adc_min'
                        => $adcMin !== null
                            ? (int) $adcMin
                            : null,

                        'measured_at'
                        => date('Y-m-d H:i:s'),
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

            $firmwareVersion =
                $this->request
                ->getHeaderLine(
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

            //--------------------------------------------------
            // NOTIFIKASI HASIL ABNORMAL KEPADA DOKTER
            //--------------------------------------------------

            if ($measurement !== null) {
                $patientId =
                    (int) $measurement['patient_id'];

                $measurementId =
                    (int) $measurement['id'];

                $qualityStatus =
                    (string) (
                        $measurement['quality_status']
                        ?? 'valid'
                    );

                /*
     * Notifikasi hanya dibuat untuk hasil
     * warning atau invalid.
     */
                if (
                    in_array(
                        $qualityStatus,
                        [
                            'warning',
                            'invalid',
                        ],
                        true
                    )
                ) {
                    $db = db_connect();

                    //--------------------------------------------------
                    // Ambil identitas pasien
                    //--------------------------------------------------

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

                    //--------------------------------------------------
                    // Cari dokter aktif yang menangani pasien
                    //--------------------------------------------------

                    $activeAssignment = $db
                        ->table('doctor_assignments')
                        ->select([
                            'doctor_id',
                            'patient_id',
                        ])
                        ->where(
                            'patient_id',
                            $patientId
                        )
                        ->where(
                            'status',
                            'active'
                        )
                        ->orderBy(
                            'assigned_at',
                            'DESC'
                        )
                        ->get()
                        ->getRowArray();

                    if (
                        $patient !== null
                        && $activeAssignment !== null
                    ) {
                        $patientName =
                            (string) $patient['name'];

                        $patientCode =
                            (string) $patient['patient_code'];

                        $systolicValue =
                            (float) $measurement['systolic'];

                        $diastolicValue =
                            (float) $measurement['diastolic'];

                        $bpmValue =
                            (int) $measurement['bpm'];

                        //--------------------------------------------------
                        // Susun isi notifikasi
                        //--------------------------------------------------

                        if ($qualityStatus === 'invalid') {
                            $notificationTitle =
                                'Hasil Pengukuran Tidak Valid';

                            $notificationMessage =
                                sprintf(
                                    '%s (%s) menghasilkan pengukuran yang tidak valid. Silakan lakukan pemeriksaan ulang.',
                                    $patientName,
                                    $patientCode
                                );

                            $notificationType =
                                'danger';
                        } else {
                            $notificationTitle =
                                'Hasil Pengukuran Perlu Perhatian';

                            $notificationMessage =
                                sprintf(
                                    '%s (%s) memperoleh hasil %.2f/%.2f mmHg dengan denyut nadi %d BPM.',
                                    $patientName,
                                    $patientCode,
                                    $systolicValue,
                                    $diastolicValue,
                                    $bpmValue
                                );

                            $notificationType =
                                'warning';
                        }

                        //--------------------------------------------------
                        // Simpan notifikasi dokter
                        //--------------------------------------------------

                        $notificationModel =
                            new NotificationModel();

                        $notificationModel
                            ->createNotification([
                                'recipient_role' =>
                                'doctor',

                                'recipient_id' =>
                                (int) $activeAssignment['doctor_id'],

                                'title' =>
                                $notificationTitle,

                                'message' =>
                                $notificationMessage,

                                'type' =>
                                $notificationType,

                                'reference_type' =>
                                'measurement',

                                'reference_id' =>
                                $measurementId,

                                'action_url' =>
                                '/doctor/patient/'
                                    . $patientId,

                                'is_read' =>
                                0,

                                'read_at' =>
                                null,
                            ]);
                    }
                }
            }

            return $this->apiResponse(
                true,
                'Hasil pengukuran berhasil disimpan.',
                [
                    'measurement' => [
                        'measurement_id'
                        => (int) $measurement['id'],

                        'request_id'
                        => (int) $measurement['request_id'],

                        'device_id'
                        => $normalizedDeviceId,

                        'patient_id'
                        => (int) $measurement['patient_id'],

                        'systolic'
                        => (float) $measurement['systolic'],

                        'map'
                        => (float) $measurement['map'],

                        'diastolic'
                        => (float) $measurement['diastolic'],

                        'bpm'
                        => (int) $measurement['bpm'],

                        'beat_count'
                        => $measurement['beat_count'] !== null
                            ? (int) $measurement['beat_count']
                            : null,

                        'quality_status'
                        => $measurement['quality_status'],

                        'is_valid'
                        => (bool) $measurement['is_valid'],

                        'failure_reason'
                        => $measurement['failure_reason'],

                        'measured_at'
                        => $measurement['measured_at'],
                    ],
                ],
                null,
                201
            );
        } catch (Throwable $exception) {
            return $this->apiResponse(
                false,
                'Terjadi kesalahan pada server.',
                null,
                [
                    'exception' =>
                    ENVIRONMENT === 'development'
                        ? $exception->getMessage()
                        : null,
                ],
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
