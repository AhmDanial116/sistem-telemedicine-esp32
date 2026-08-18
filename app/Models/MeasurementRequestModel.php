<?php

namespace App\Models;

use CodeIgniter\Model;

class MeasurementRequestModel extends Model
{
    protected $table = 'measurement_requests';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'request_code',
        'patient_id',
        'device_record_id',
        'requested_by_role',
        'requested_by_id',
        'status',
        'requested_at',
        'acknowledged_at',
        'started_at',
        'completed_at',
        'expired_at',
        'error_code',
        'error_message',
    ];

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $deletedField = '';

    protected $validationRules = [
        'request_code' => [
            'label' => 'Kode request',
            'rules' => 'required|max_length[40]|is_unique[measurement_requests.request_code,id,{id}]',
        ],

        'patient_id' => [
            'label' => 'Pasien',
            'rules' => 'required|is_natural_no_zero',
        ],

        'device_record_id' => [
            'label' => 'Perangkat',
            'rules' => 'required|is_natural_no_zero',
        ],

        'requested_by_role' => [
            'label' => 'Role peminta',
            'rules' => 'required|in_list[patient,doctor,admin]',
        ],

        'requested_by_id' => [
            'label' => 'ID peminta',
            'rules' => 'permit_empty|is_natural_no_zero',
        ],

        'status' => [
            'label' => 'Status request',
            'rules' => 'required|in_list[pending,acknowledged,measuring_bpm,inflating,recording,processing,completed,failed,expired,cancelled]',
        ],

        'error_code' => [
            'label' => 'Kode error',
            'rules' => 'permit_empty|max_length[50]',
        ],
    ];

    protected $validationMessages = [
        'request_code' => [
            'required' => 'Kode request wajib diisi.',
            'max_length' => 'Kode request maksimal 40 karakter.',
            'is_unique' => 'Kode request sudah digunakan.',
        ],

        'patient_id' => [
            'required' => 'Patient ID wajib diisi.',
            'is_natural_no_zero' => 'Patient ID tidak valid.',
        ],

        'device_record_id' => [
            'required' => 'Device record ID wajib diisi.',
            'is_natural_no_zero' => 'Device record ID tidak valid.',
        ],

        'requested_by_role' => [
            'required' => 'Role peminta wajib diisi.',
            'in_list' => 'Role peminta tidak valid.',
        ],

        'requested_by_id' => [
            'is_natural_no_zero' => 'ID peminta tidak valid.',
        ],

        'status' => [
            'required' => 'Status request wajib diisi.',
            'in_list' => 'Status request tidak valid.',
        ],

        'error_code' => [
            'max_length' => 'Kode error maksimal 50 karakter.',
        ],
    ];

    protected $skipValidation = false;

    protected $cleanValidationRules = true;

    /**
     * Daftar status yang masih dianggap aktif.
     */
    private array $activeStatuses = [
        'pending',
        'acknowledged',
        'measuring_bpm',
        'inflating',
        'recording',
        'processing',
    ];

    /**
     * Membuat kode request unik.
     *
     * Contoh:
     * MR-20260804-A1B2C3D4
     */
    public function generateRequestCode(): string
    {
        do {
            $code = 'MR-'
                . date('Ymd')
                . '-'
                . strtoupper(bin2hex(random_bytes(4)));

            $exists = $this
                ->where('request_code', $code)
                ->first();
        } while ($exists !== null);

        return $code;
    }

    /**
     * Membuat request pengukuran baru.
     */
    public function createRequest(
        int $patientId,
        int $deviceRecordId,
        string $requestedByRole,
        ?int $requestedById = null,
        int $expirationMinutes = 5
    ): array {
        if ($expirationMinutes < 1) {
            $expirationMinutes = 1;
        }

        if ($this->hasActiveRequest($deviceRecordId)) {
            return [
                'success' => false,
                'message' => 'Perangkat masih memiliki request pengukuran aktif.',
                'request_id' => null,
                'request_code' => null,
            ];
        }

        $requestCode = $this->generateRequestCode();

        $now = date('Y-m-d H:i:s');

        $expiredAt = date(
            'Y-m-d H:i:s',
            strtotime("+{$expirationMinutes} minutes")
        );

        $data = [
            'request_code' => $requestCode,
            'patient_id' => $patientId,
            'device_record_id' => $deviceRecordId,
            'requested_by_role' => $requestedByRole,
            'requested_by_id' => $requestedById,
            'status' => 'pending',
            'requested_at' => $now,
            'expired_at' => $expiredAt,
        ];

        $requestId = $this->insert($data, true);

        if ($requestId === false) {
            return [
                'success' => false,
                'message' => 'Request pengukuran gagal dibuat.',
                'errors' => $this->errors(),
                'request_id' => null,
                'request_code' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Request pengukuran berhasil dibuat.',
            'request_id' => (int) $requestId,
            'request_code' => $requestCode,
        ];
    }

    /**
     * Cek apakah perangkat masih memiliki request aktif.
     */
    public function hasActiveRequest(int $deviceRecordId): bool
    {
        return $this
            ->where('device_record_id', $deviceRecordId)
            ->whereIn('status', $this->activeStatuses)
            ->first() !== null;
    }

    /**
     * Mengambil request aktif terbaru untuk perangkat.
     */
    public function getActiveRequestByDeviceRecordId(
        int $deviceRecordId
    ): ?array {
        return $this
            ->where('device_record_id', $deviceRecordId)
            ->whereIn('status', $this->activeStatuses)
            ->orderBy('requested_at', 'ASC')
            ->first();
    }

    /**
     * Mengambil request pending berdasarkan Device ID/MAC.
     *
     * Method ini akan dipakai oleh ESP32 saat polling.
     */
    public function getPendingRequestByDeviceId(
        string $deviceId
    ): ?array {
        $deviceId = strtoupper(
            str_replace([':', '-', ' '], '', trim($deviceId))
        );

        return $this
            ->select([
                'measurement_requests.id',
                'measurement_requests.request_code',
                'measurement_requests.patient_id',
                'measurement_requests.device_record_id',
                'measurement_requests.requested_by_role',
                'measurement_requests.requested_by_id',
                'measurement_requests.status',
                'measurement_requests.requested_at',
                'measurement_requests.expired_at',
                'devices.device_id',
                'devices.firmware_version',
                'patients.patient_code',
                'patients.name AS patient_name',
            ])
            ->join(
                'devices',
                'devices.id = measurement_requests.device_record_id',
                'inner'
            )
            ->join(
                'patients',
                'patients.id = measurement_requests.patient_id',
                'inner'
            )
            ->where('devices.device_id', $deviceId)
            ->where('devices.status', 'active')
            ->where('measurement_requests.status', 'pending')
            ->orderBy('measurement_requests.requested_at', 'ASC')
            ->first();
    }

    /**
     * Cari request berdasarkan kode unik.
     */
    public function findByRequestCode(string $requestCode): ?array
    {
        return $this
            ->where('request_code', trim($requestCode))
            ->first();
    }

    /**
     * Ubah status request.
     */
    public function updateStatus(
        int $requestId,
        string $status,
        ?string $errorCode = null,
        ?string $errorMessage = null
    ): bool {
        $validStatuses = [
            'pending',
            'acknowledged',
            'measuring_bpm',
            'inflating',
            'recording',
            'processing',
            'completed',
            'failed',
            'expired',
            'cancelled',
        ];

        if (! in_array($status, $validStatuses, true)) {
            return false;
        }

        $data = [
            'status' => $status,
        ];

        $now = date('Y-m-d H:i:s');

        switch ($status) {
            case 'acknowledged':
                $data['acknowledged_at'] = $now;
                break;

            case 'measuring_bpm':
            case 'inflating':
            case 'recording':
            case 'processing':
                $request = $this->find($requestId);

                if (
                    $request !== null
                    && empty($request['started_at'])
                ) {
                    $data['started_at'] = $now;
                }
                break;

            case 'completed':
            case 'failed':
            case 'cancelled':
                $data['completed_at'] = $now;
                break;
        }

        if ($errorCode !== null) {
            $data['error_code'] = trim($errorCode);
        }

        if ($errorMessage !== null) {
            $data['error_message'] = trim($errorMessage);
        }

        return $this->update($requestId, $data);
    }

    public function markAcknowledged(int $requestId): bool
    {
        return $this->updateStatus(
            $requestId,
            'acknowledged'
        );
    }

    public function markMeasuringBpm(int $requestId): bool
    {
        return $this->updateStatus(
            $requestId,
            'measuring_bpm'
        );
    }

    public function markInflating(int $requestId): bool
    {
        return $this->updateStatus(
            $requestId,
            'inflating'
        );
    }

    public function markRecording(int $requestId): bool
    {
        return $this->updateStatus(
            $requestId,
            'recording'
        );
    }

    public function markProcessing(int $requestId): bool
    {
        return $this->updateStatus(
            $requestId,
            'processing'
        );
    }

    public function markCompleted(int $requestId): bool
    {
        return $this->updateStatus(
            $requestId,
            'completed'
        );
    }

    public function markFailed(
        int $requestId,
        string $errorCode,
        string $errorMessage
    ): bool {
        return $this->updateStatus(
            $requestId,
            'failed',
            $errorCode,
            $errorMessage
        );
    }

    public function markCancelled(int $requestId): bool
    {
        return $this->updateStatus(
            $requestId,
            'cancelled'
        );
    }

    /**
     * Tandai request pending yang sudah melewati expired_at.
     */
    public function expireOldRequests(): int
    {
        $now = date('Y-m-d H:i:s');

        $requests = $this
            ->whereIn('status', [
                'pending',
                'acknowledged',
            ])
            ->where('expired_at IS NOT NULL', null, false)
            ->where('expired_at <', $now)
            ->findAll();

        $expiredCount = 0;

        foreach ($requests as $request) {
            $updated = $this->update(
                $request['id'],
                [
                    'status' => 'expired',
                    'completed_at' => $now,
                    'error_code' => 'REQUEST_EXPIRED',
                    'error_message' => 'Request pengukuran telah kedaluwarsa.',
                ]
            );

            if ($updated) {
                $expiredCount++;
            }
        }

        return $expiredCount;
    }

    /**
     * Ambil request beserta pasien dan perangkat.
     */
    public function getRequestDetail(int $requestId): ?array
    {
        return $this
            ->select([
                'measurement_requests.*',

                'devices.device_id',
                'devices.device_name',
                'devices.last_seen_at',
                'devices.status AS device_status',

                'patients.patient_code',
                'patients.name AS patient_name',
                'patients.status AS patient_status',
            ])
            ->join(
                'devices',
                'devices.id = measurement_requests.device_record_id',
                'inner'
            )
            ->join(
                'patients',
                'patients.id = measurement_requests.patient_id',
                'inner'
            )
            ->where(
                'measurement_requests.id',
                $requestId
            )
            ->first();
    }

    /**
     * Riwayat request pasien.
     */
    public function getPatientRequestHistory(
        int $patientId,
        int $limit = 20
    ): array {
        if ($limit < 1) {
            $limit = 20;
        }

        return $this
            ->where('patient_id', $patientId)
            ->orderBy('requested_at', 'DESC')
            ->findAll($limit);
    }

    /**
     * Request terbaru pasien.
     */
    public function getLatestPatientRequest(
        int $patientId
    ): ?array {
        return $this
            ->where('patient_id', $patientId)
            ->orderBy('requested_at', 'DESC')
            ->first();
    }

    /**
     * Hitung request berdasarkan status.
     */
    public function countByStatus(string $status): int
    {
        return $this
            ->where('status', $status)
            ->countAllResults();
    }
}
