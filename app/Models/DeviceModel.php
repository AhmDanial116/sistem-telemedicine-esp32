<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTimeInterface;

class DeviceModel extends Model
{
    /**
     * Nama tabel database.
     */
    protected $table = 'devices';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Primary key menggunakan auto increment.
     */
    protected $useAutoIncrement = true;

    /**
     * Hasil query dikembalikan sebagai array.
     */
    protected $returnType = 'array';

    /**
     * Soft delete tidak digunakan.
     *
     * Perangkat yang tidak dipakai akan diubah statusnya
     * menjadi inactive, maintenance, atau replaced.
     */
    protected $useSoftDeletes = false;

    /**
     * Kolom yang boleh diisi melalui insert() atau update().
     *
     * active_patient_key tidak dimasukkan karena merupakan
     * generated column dari database.
     */
    protected $allowedFields = [
        'device_id',
        'patient_id',
        'device_name',
        'firmware_version',
        'last_ip_address',
        'last_seen_at',
        'status',
        'registered_at',
    ];

    /**
     * Gunakan created_at dan updated_at otomatis.
     */
    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    /**
     * Tidak ada deleted_at karena soft delete tidak digunakan.
     */
    protected $deletedField = '';

    /**
     * Validasi dasar data perangkat.
     */
    protected $validationRules = [
        'device_id' => [
            'label' => 'Device ID',
            'rules' => 'required|exact_length[12]|alpha_numeric',
        ],

        'patient_id' => [
            'label' => 'Pasien',
            'rules' => 'permit_empty|is_natural_no_zero',
        ],

        'device_name' => [
            'label' => 'Nama perangkat',
            'rules' => 'permit_empty|max_length[100]',
        ],

        'firmware_version' => [
            'label' => 'Versi firmware',
            'rules' => 'permit_empty|max_length[30]',
        ],

        'last_ip_address' => [
            'label' => 'Alamat IP',
            'rules' => 'permit_empty|max_length[45]',
        ],

        'status' => [
            'label' => 'Status perangkat',
            'rules' => 'required|in_list[active,inactive,maintenance,replaced]',
        ],
    ];

    protected $validationMessages = [
        'device_id' => [
            'required' => 'Device ID wajib diisi.',
            'exact_length' => 'Device ID harus terdiri dari tepat 12 karakter.',
            'alpha_numeric' => 'Device ID hanya boleh berisi huruf dan angka.',
        ],

        'patient_id' => [
            'is_natural_no_zero' => 'Patient ID tidak valid.',
        ],

        'device_name' => [
            'max_length' => 'Nama perangkat maksimal 100 karakter.',
        ],

        'firmware_version' => [
            'max_length' => 'Versi firmware maksimal 30 karakter.',
        ],

        'last_ip_address' => [
            'max_length' => 'Alamat IP maksimal 45 karakter.',
        ],

        'status' => [
            'required' => 'Status perangkat wajib diisi.',
            'in_list' => 'Status perangkat tidak valid.',
        ],
    ];

    protected $skipValidation = false;

    protected $cleanValidationRules = true;

    /**
     * Normalisasi Device ID.
     *
     * Contoh:
     * 84:1F:E8:2B:EF:BC
     * menjadi:
     * 841FE82BEFBC
     */
    public function normalizeDeviceId(string $deviceId): string
    {
        $normalized = str_replace(
            [':', '-', ' '],
            '',
            trim($deviceId)
        );

        return strtoupper($normalized);
    }

    public function registerDevice(
        string $deviceId,
        ?string $ipAddress = null,
        ?string $firmwareVersion = null
    ): bool {

        $deviceId = $this->normalizeDeviceId($deviceId);

        if ($this->existsByDeviceId($deviceId)) {
            return true;
        }

        return $this->insert([
            'device_id'        => $deviceId,
            'patient_id'       => null,
            'device_name'      => 'ESP32 ' . substr($deviceId, -6),
            'firmware_version' => $firmwareVersion,
            'last_ip_address'  => $ipAddress,
            'last_seen_at'     => date('Y-m-d H:i:s'),
            'registered_at'    => date('Y-m-d H:i:s'),

            // sementara inactive
            'status'           => 'inactive'
        ]) !== false;
    }

    /**
     * Cari perangkat berdasarkan MAC address atau Device ID.
     *
     * Input dapat berupa:
     * 84:1F:E8:2B:EF:BC
     * atau:
     * 841FE82BEFBC
     */
    public function findByDeviceId(string $deviceId): ?array
    {
        $normalizedDeviceId = $this->normalizeDeviceId($deviceId);

        return $this
            ->where('device_id', $normalizedDeviceId)
            ->first();
    }

    /**
     * Cari perangkat aktif berdasarkan Device ID.
     */
    public function findActiveByDeviceId(string $deviceId): ?array
    {
        $normalizedDeviceId = $this->normalizeDeviceId($deviceId);

        return $this
            ->where('device_id', $normalizedDeviceId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Cari perangkat aktif milik pasien tertentu.
     */
    public function findActiveByPatientId(int $patientId): ?array
    {
        return $this
            ->where('patient_id', $patientId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Ambil perangkat beserta data pasien.
     *
     * Dipakai oleh:
     * - identifikasi pasien;
     * - validasi upload ESP32;
     * - dashboard admin.
     */
    public function getDeviceWithPatient(string $deviceId): ?array
    {
        $normalizedDeviceId = $this->normalizeDeviceId($deviceId);

        return $this
            ->select([
                'devices.id',
                'devices.device_id',
                'devices.patient_id',
                'devices.device_name',
                'devices.firmware_version',
                'devices.last_ip_address',
                'devices.last_seen_at',
                'devices.status AS device_status',
                'devices.registered_at',

                'patients.patient_code',
                'patients.name AS patient_name',
                'patients.birth_date',
                'patients.gender',
                'patients.phone AS patient_phone',
                'patients.address AS patient_address',
                'patients.status AS patient_status',
            ])
            ->join(
                'patients',
                'patients.id = devices.patient_id',
                'left'
            )
            ->where(
                'devices.device_id',
                $normalizedDeviceId
            )
            ->first();
    }

    /**
     * Ambil perangkat aktif beserta data pasien aktif.
     *
     * Cocok dipakai untuk halaman identifikasi pasien.
     */
    public function getActiveDeviceWithPatient(string $deviceId): ?array
    {
        $normalizedDeviceId = $this->normalizeDeviceId($deviceId);

        return $this
            ->select([
                'devices.id',
                'devices.device_id',
                'devices.patient_id',
                'devices.device_name',
                'devices.firmware_version',
                'devices.last_ip_address',
                'devices.last_seen_at',
                'devices.status AS device_status',

                'patients.patient_code',
                'patients.name AS patient_name',
                'patients.birth_date',
                'patients.gender',
                'patients.phone AS patient_phone',
                'patients.address AS patient_address',
                'patients.status AS patient_status',
            ])
            ->join(
                'patients',
                'patients.id = devices.patient_id',
                'inner'
            )
            ->where(
                'devices.device_id',
                $normalizedDeviceId
            )
            ->where(
                'devices.status',
                'active'
            )
            ->where(
                'patients.status',
                'active'
            )
            ->first();
    }

    /**
     * Cek apakah perangkat terdaftar.
     */
    public function existsByDeviceId(string $deviceId): bool
    {
        return $this->findByDeviceId($deviceId) !== null;
    }

    /**
     * Cek apakah perangkat aktif.
     */
    public function isActive(string $deviceId): bool
    {
        return $this->findActiveByDeviceId($deviceId) !== null;
    }

    /**
     * Perbarui waktu terakhir ESP32 terhubung.
     *
     * Method ini juga dapat memperbarui:
     * - IP address;
     * - versi firmware.
     */
    public function updateLastSeen(
        string $deviceId,
        ?string $ipAddress = null,
        ?string $firmwareVersion = null
    ): bool {
        $device = $this->findByDeviceId($deviceId);

        if ($device === null) {
            return false;
        }

        $updateData = [
            'last_seen_at' => date('Y-m-d H:i:s'),
        ];

        if (
            $ipAddress !== null
            && trim($ipAddress) !== ''
        ) {
            $updateData['last_ip_address'] = trim($ipAddress);
        }

        if (
            $firmwareVersion !== null
            && trim($firmwareVersion) !== ''
        ) {
            $updateData['firmware_version'] = trim($firmwareVersion);
        }

        return $this->update(
            $device['id'],
            $updateData
        );
    }

    /**
     * Perbarui hanya versi firmware.
     */
    public function updateFirmwareVersion(
        string $deviceId,
        string $firmwareVersion
    ): bool {
        $device = $this->findByDeviceId($deviceId);

        if ($device === null) {
            return false;
        }

        return $this->update(
            $device['id'],
            [
                'firmware_version' => trim($firmwareVersion),
            ]
        );
    }

    /**
     * Perbarui hanya alamat IP terakhir.
     */
    public function updateLastIpAddress(
        string $deviceId,
        string $ipAddress
    ): bool {
        $device = $this->findByDeviceId($deviceId);

        if ($device === null) {
            return false;
        }

        return $this->update(
            $device['id'],
            [
                'last_ip_address' => trim($ipAddress),
            ]
        );
    }

    /**
     * Hubungkan perangkat kepada pasien.
     */
    public function assignToPatient(
        int $deviceRecordId,
        int $patientId
    ): bool {
        return $this->update(
            $deviceRecordId,
            [
                'patient_id' => $patientId,
                'status' => 'active',
            ]
        );
    }

    /**
     * Lepaskan hubungan perangkat dari pasien.
     *
     * Status perangkat dibuat inactive agar tidak dapat
     * digunakan untuk identifikasi maupun upload.
     */
    public function unassignFromPatient(
        int $deviceRecordId
    ): bool {
        return $this->update(
            $deviceRecordId,
            [
                'patient_id' => null,
                'status' => 'inactive',
            ]
        );
    }

    /**
     * Ubah status perangkat.
     */
    public function changeStatus(
        int $deviceRecordId,
        string $status
    ): bool {
        $validStatuses = [
            'active',
            'inactive',
            'maintenance',
            'replaced',
        ];

        if (! in_array($status, $validStatuses, true)) {
            return false;
        }

        return $this->update(
            $deviceRecordId,
            [
                'status' => $status,
            ]
        );
    }

    /**
     * Tandai perangkat lama sebagai replaced.
     */
    public function markAsReplaced(
        int $deviceRecordId
    ): bool {
        return $this->changeStatus(
            $deviceRecordId,
            'replaced'
        );
    }

    /**
     * Ambil seluruh perangkat beserta nama pasien.
     *
     * Dipakai pada dashboard admin.
     */
    public function getAllWithPatients(): array
    {
        return $this
            ->select([
                'devices.id',
                'devices.device_id',
                'devices.device_name',
                'devices.firmware_version',
                'devices.last_ip_address',
                'devices.last_seen_at',
                'devices.status',
                'devices.registered_at',

                'patients.id AS patient_id',
                'patients.patient_code',
                'patients.name AS patient_name',
            ])
            ->join(
                'patients',
                'patients.id = devices.patient_id',
                'left'
            )
            ->orderBy(
                'devices.created_at',
                'DESC'
            )
            ->findAll();
    }

    /**
     * Ambil perangkat berdasarkan status.
     */
    public function getByStatus(string $status): array
    {
        return $this
            ->where('status', $status)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Hitung jumlah perangkat berdasarkan status.
     */
    public function countByStatus(string $status): int
    {
        return $this
            ->where('status', $status)
            ->countAllResults();
    }

    /**
     * Ambil daftar perangkat yang belum terhubung
     * ke pasien.
     */
    public function getUnassignedDevices(): array
    {
        return $this
            ->where('patient_id', null)
            ->whereIn(
                'status',
                ['active', 'maintenance']
            )
            ->orderBy(
                'created_at',
                'DESC'
            )
            ->findAll();
    }

    /**
     * Ambil daftar perangkat yang dianggap online.
     *
     * Perangkat dianggap online bila last_seen_at masih
     * berada dalam rentang menit yang ditentukan.
     */
    public function getOnlineDevices(
        int $minutes = 2
    ): array {
        if ($minutes < 1) {
            $minutes = 1;
        }

        $threshold = date(
            'Y-m-d H:i:s',
            strtotime("-{$minutes} minutes")
        );

        return $this
            ->where('status', 'active')
            ->where(
                'last_seen_at >=',
                $threshold
            )
            ->orderBy(
                'last_seen_at',
                'DESC'
            )
            ->findAll();
    }

    /**
     * Cek apakah perangkat dianggap online.
     */
    public function isOnline(
        string $deviceId,
        int $minutes = 2
    ): bool {
        $device = $this->findActiveByDeviceId($deviceId);

        if (
            $device === null
            || empty($device['last_seen_at'])
        ) {
            return false;
        }

        if ($minutes < 1) {
            $minutes = 1;
        }

        $lastSeenTimestamp = strtotime(
            $device['last_seen_at']
        );

        $thresholdTimestamp = strtotime(
            "-{$minutes} minutes"
        );

        return $lastSeenTimestamp >= $thresholdTimestamp;
    }
}
