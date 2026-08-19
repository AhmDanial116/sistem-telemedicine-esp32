<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\DeviceModel;
use App\Models\MeasurementRequestModel;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class DeviceController extends BaseController
{
    protected DeviceModel $deviceModel;

    protected MeasurementRequestModel $requestModel;

    public function __construct()
    {
        $this->deviceModel = new DeviceModel();

        $this->requestModel =
            new MeasurementRequestModel();
    }

    /**
     * Format response API yang konsisten.
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
     * GET /api/v1/device/{device_id}/request
     *
     * Dipanggil ESP32 secara berkala untuk mengecek
     * apakah ada permintaan pengukuran baru.
     */
    public function request(
        string $deviceId
    ): ResponseInterface {
        try {
            //------------------------------------------------
            // Normalisasi Device ID
            //------------------------------------------------

            $normalizedDeviceId =
                $this->deviceModel
                ->normalizeDeviceId($deviceId);

            //------------------------------------------------
            // Validasi format Device ID
            //------------------------------------------------

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
                    400
                );
            }

            //------------------------------------------------
            // Ambil IP ESP32
            //------------------------------------------------

            $ipAddress =
                $this->request->getIPAddress();

            $firmwareVersion =
                $this->request
                ->getHeaderLine(
                    'X-Firmware-Version'
                );

            //------------------------------------------------
            // Cari perangkat aktif dan pasien
            //------------------------------------------------

            $device =
                $this->deviceModel
                ->findByDeviceId(
                    $normalizedDeviceId
                );

            if ($device === null) {

                $this->deviceModel->registerDevice(
                    $normalizedDeviceId,
                    $ipAddress,
                    $firmwareVersion
                );

                return $this->apiResponse(
                    true,
                    'Perangkat berhasil diregistrasi.',
                    [
                        'registered' => true,
                        'device_id' => $normalizedDeviceId,
                        'has_request' => false
                    ]
                );
            }

            //------------------------------------------------
            // Update heartbeat perangkat
            //------------------------------------------------

            $this->deviceModel->updateLastSeen(
                $normalizedDeviceId,
                $ipAddress,
                $firmwareVersion !== ''
                    ? $firmwareVersion
                    : null
            );

            //------------------------------------------------
            // Ubah request lama yang kedaluwarsa
            //------------------------------------------------

            $this->requestModel
                ->expireOldRequests();

            //------------------------------------------------
            // Cari request pending
            //------------------------------------------------

            $pendingRequest =
                $this->requestModel
                ->getPendingRequestByDeviceId(
                    $normalizedDeviceId
                );

            //------------------------------------------------
            // Tidak ada request
            //------------------------------------------------

            if ($pendingRequest === null) {
                return $this->apiResponse(
                    true,
                    'Tidak ada permintaan pengukuran.',
                    [
                        'has_request' => false,
                        'device_id' =>
                        $normalizedDeviceId,
                        'server_time' =>
                        date('Y-m-d H:i:s'),
                    ]
                );
            }

            //------------------------------------------------
            // Tandai bahwa ESP32 sudah menerima request
            //------------------------------------------------

            $acknowledged =
                $this->requestModel
                ->markAcknowledged(
                    (int) $pendingRequest['id']
                );

            if (! $acknowledged) {
                return $this->apiResponse(
                    false,
                    'Status request gagal diperbarui.',
                    null,
                    [
                        'request_id' =>
                        $pendingRequest['id'],
                    ],
                    500
                );
            }

            //------------------------------------------------
            // Response request ke ESP32
            //------------------------------------------------

            return $this->apiResponse(
                true,
                'Permintaan pengukuran ditemukan.',
                [
                    'has_request' => true,

                    'request' => [
                        'request_id' =>
                        (int) $pendingRequest['id'],

                        'request_code' =>
                        $pendingRequest['request_code'],

                        'measurement_type' =>
                        $pendingRequest['measurement_type'],

                        'patient_id' =>
                        (int) $pendingRequest['patient_id'],

                        'patient_code' =>
                        $pendingRequest['patient_code'],

                        'patient_name' =>
                        $pendingRequest['patient_name'],

                        'device_id' =>
                        $pendingRequest['device_id'],

                        'status' =>
                        'acknowledged',

                        'requested_at' =>
                        $pendingRequest['requested_at'],

                        'expired_at' =>
                        $pendingRequest['expired_at'],
                    ],

                    'server_time' =>
                    date('Y-m-d H:i:s'),
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
     * GET /api/v1/device/{device_id}
     *
     * Endpoint tambahan untuk mengecek perangkat.
     */
    public function show(
        string $deviceId
    ): ResponseInterface {
        $device =
            $this->deviceModel
            ->getActiveDeviceWithPatient(
                $deviceId
            );

        if ($device === null) {
            return $this->apiResponse(
                false,
                'Perangkat tidak ditemukan.',
                null,
                null,
                404
            );
        }

        return $this->apiResponse(
            true,
            'Perangkat ditemukan.',
            [
                'device' => $device,
                'online' =>
                $this->deviceModel
                    ->isOnline($deviceId),
            ]
        );
    }
}
