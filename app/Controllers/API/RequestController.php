<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\DeviceModel;
use App\Models\MeasurementRequestModel;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class RequestController extends BaseController
{
    protected MeasurementRequestModel $requestModel;

    protected DeviceModel $deviceModel;

    public function __construct()
    {
        $this->requestModel =
            new MeasurementRequestModel();

        $this->deviceModel =
            new DeviceModel();
    }

    /**
     * Format respons API yang konsisten.
     */
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
     * POST /api/v1/request/status
     *
     * Body JSON:
     * {
     *   "request_id": 1,
     *   "device_id": "841FE82BEFBC",
     *   "status": "inflating",
     *   "error_code": null,
     *   "error_message": null
     * }
     */
    public function updateStatus(): ResponseInterface
    {
        try {
            //------------------------------------------------
            // Ambil body JSON
            //------------------------------------------------

            $parsedBody =
                $this->parseJsonBody();

            if (! $parsedBody['success']) {
                return $this->apiResponse(
                    false,
                    'Body request harus berupa JSON.',
                    null,
                    $parsedBody['diagnostic'],
                    400
                );
            }

            $payload = $parsedBody['payload'];

            //------------------------------------------------
            // Ambil field
            //------------------------------------------------

            $requestId = isset($payload['request_id'])
                ? (int) $payload['request_id']
                : 0;

            $deviceId = isset($payload['device_id'])
                ? trim((string) $payload['device_id'])
                : '';

            $status = isset($payload['status'])
                ? trim((string) $payload['status'])
                : '';

            $errorCode = isset($payload['error_code'])
                ? trim((string) $payload['error_code'])
                : null;

            $errorMessage = isset($payload['error_message'])
                ? trim((string) $payload['error_message'])
                : null;

            //------------------------------------------------
            // Validasi field wajib
            //------------------------------------------------

            $errors = [];

            if ($requestId <= 0) {
                $errors['request_id'] =
                    'Request ID harus berupa angka positif.';
            }

            if ($deviceId === '') {
                $errors['device_id'] =
                    'Device ID wajib diisi.';
            }

            if ($status === '') {
                $errors['status'] =
                    'Status wajib diisi.';
            }

            if ($errors !== []) {
                return $this->apiResponse(
                    false,
                    'Data request tidak lengkap.',
                    null,
                    $errors,
                    422
                );
            }

            //------------------------------------------------
            // Normalisasi Device ID
            //------------------------------------------------

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

            //------------------------------------------------
            // Validasi status
            //------------------------------------------------

            $validStatuses = [
                'acknowledged',
                'measuring_bpm',
                'inflating',
                'recording',
                'processing',
                'completed',
                'failed',
                'cancelled',
            ];

            if (! in_array($status, $validStatuses, true)) {
                return $this->apiResponse(
                    false,
                    'Status request tidak valid.',
                    null,
                    [
                        'status' =>
                        'Status harus acknowledged, measuring_bpm, inflating, recording, processing, completed, failed, atau cancelled.',
                    ],
                    422
                );
            }

            //------------------------------------------------
            // Cari request
            //------------------------------------------------

            $request =
                $this->requestModel->find($requestId);

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

            //------------------------------------------------
            // Cari perangkat
            //------------------------------------------------

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

            //------------------------------------------------
            // Pastikan request memang milik perangkat
            //------------------------------------------------

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
                        'device_id' =>
                        $normalizedDeviceId,
                    ],
                    403
                );
            }

            //------------------------------------------------
            // Tolak perubahan request yang sudah final
            //------------------------------------------------

            $finalStatuses = [
                'completed',
                'failed',
                'expired',
                'cancelled',
            ];

            if (
                in_array(
                    $request['status'],
                    $finalStatuses,
                    true
                )
            ) {
                return $this->apiResponse(
                    false,
                    'Request sudah berada pada status akhir.',
                    [
                        'request_id' =>
                        (int) $request['id'],
                        'current_status' =>
                        $request['status'],
                    ],
                    null,
                    409
                );
            }

            //------------------------------------------------
            // Validasi error jika status failed
            //------------------------------------------------

            if ($status === 'failed') {
                if (
                    $errorCode === null
                    || $errorCode === ''
                ) {
                    $errorCode = 'MEASUREMENT_FAILED';
                }

                if (
                    $errorMessage === null
                    || $errorMessage === ''
                ) {
                    $errorMessage =
                        'Pengukuran gagal tanpa keterangan tambahan.';
                }
            } else {
                $errorCode = null;
                $errorMessage = null;
            }

            //------------------------------------------------
            // Update status
            //------------------------------------------------

            $updated =
                $this->requestModel
                ->updateStatus(
                    $requestId,
                    $status,
                    $errorCode,
                    $errorMessage
                );

            if (! $updated) {
                return $this->apiResponse(
                    false,
                    'Status request gagal diperbarui.',
                    null,
                    $this->requestModel->errors(),
                    500
                );
            }

            //------------------------------------------------
            // Perbarui last_seen perangkat
            //------------------------------------------------

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

            //------------------------------------------------
            // Ambil detail terbaru
            //------------------------------------------------

            $updatedRequest =
                $this->requestModel
                ->getRequestDetail($requestId);

            return $this->apiResponse(
                true,
                'Status request berhasil diperbarui.',
                [
                    'request' => [
                        'request_id' =>
                        (int) $updatedRequest['id'],

                        'request_code' =>
                        $updatedRequest['request_code'],

                        'device_id' =>
                        $updatedRequest['device_id'],

                        'status' =>
                        $updatedRequest['status'],

                        'acknowledged_at' =>
                        $updatedRequest['acknowledged_at'],

                        'started_at' =>
                        $updatedRequest['started_at'],

                        'completed_at' =>
                        $updatedRequest['completed_at'],

                        'error_code' =>
                        $updatedRequest['error_code'],

                        'error_message' =>
                        $updatedRequest['error_message'],
                    ],
                ]
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
     * GET /api/v1/request/{request_id}
     *
     * Dipakai website untuk memantau status secara berkala.
     */
    public function show(
        int $requestId
    ): ResponseInterface {
        $request =
            $this->requestModel
            ->getRequestDetail($requestId);

        if ($request === null) {
            return $this->apiResponse(
                false,
                'Request pengukuran tidak ditemukan.',
                null,
                null,
                404
            );
        }

        return $this->apiResponse(
            true,
            'Detail request ditemukan.',
            [
                'request' => [
                    'request_id' =>
                    (int) $request['id'],

                    'request_code' =>
                    $request['request_code'],

                    'device_id' =>
                    $request['device_id'],

                    'patient_id' =>
                    (int) $request['patient_id'],

                    'patient_code' =>
                    $request['patient_code'],

                    'patient_name' =>
                    $request['patient_name'],

                    'status' =>
                    $request['status'],

                    'requested_at' =>
                    $request['requested_at'],

                    'acknowledged_at' =>
                    $request['acknowledged_at'],

                    'started_at' =>
                    $request['started_at'],

                    'completed_at' =>
                    $request['completed_at'],

                    'expired_at' =>
                    $request['expired_at'],

                    'error_code' =>
                    $request['error_code'],

                    'error_message' =>
                    $request['error_message'],
                ],
            ]
        );
    }
}
