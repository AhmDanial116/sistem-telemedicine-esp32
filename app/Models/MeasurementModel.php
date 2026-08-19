<?php

namespace App\Models;

use CodeIgniter\Model;
use Throwable;

class MeasurementModel extends Model
{
    protected $table = 'measurements';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'request_id',
        'patient_id',
        'device_record_id',
        'systolic',
        'map',
        'diastolic',
        'bpm',
        'beat_count',
        'pressure_baseline_adc',
        'adc_max',
        'adc_min',
        'is_valid',
        'quality_status',
        'failure_reason',
        'measured_at',
    ];

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $deletedField = '';

    protected $validationRules = [
        'request_id' => [
            'label' => 'Request pengukuran',
            'rules' => 'permit_empty|is_natural_no_zero',
        ],

        'patient_id' => [
            'label' => 'Pasien',
            'rules' => 'required|is_natural_no_zero',
        ],

        'device_record_id' => [
            'label' => 'Perangkat',
            'rules' => 'required|is_natural_no_zero',
        ],

        'systolic' => [
            'label' => 'Sistolik',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[40]|less_than_equal_to[300]',
        ],

        'map' => [
            'label' => 'MAP',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[30]|less_than_equal_to[250]',
        ],

        'diastolic' => [
            'label' => 'Diastolik',
            'rules' => 'permit_empty|decimal|greater_than_equal_to[20]|less_than_equal_to[200]',
        ],

        'bpm' => [
            'label' => 'BPM',
            'rules' => 'permit_empty|integer|greater_than_equal_to[30]|less_than_equal_to[220]',
        ],

        'beat_count' => [
            'label' => 'Jumlah beat',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
        ],

        'pressure_baseline_adc' => [
            'label' => 'Baseline ADC',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[4095]',
        ],

        'adc_max' => [
            'label' => 'ADC maksimum',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[4095]',
        ],

        'adc_min' => [
            'label' => 'ADC minimum',
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[4095]',
        ],

        'is_valid' => [
            'label' => 'Validitas hasil',
            'rules' => 'required|in_list[0,1]',
        ],

        'quality_status' => [
            'label' => 'Status kualitas',
            'rules' => 'required|in_list[valid,warning,invalid]',
        ],

        'failure_reason' => [
            'label' => 'Alasan kegagalan',
            'rules' => 'permit_empty|max_length[100]',
        ],
    ];

    protected $validationMessages = [
        'patient_id' => [
            'required' => 'Patient ID wajib diisi.',
            'is_natural_no_zero' => 'Patient ID tidak valid.',
        ],

        'device_record_id' => [
            'required' => 'Device record ID wajib diisi.',
            'is_natural_no_zero' => 'Device record ID tidak valid.',
        ],

        'systolic' => [
            'required' => 'Nilai sistolik wajib diisi.',
            'decimal' => 'Nilai sistolik harus berupa angka.',
            'greater_than_equal_to' => 'Nilai sistolik terlalu rendah.',
            'less_than_equal_to' => 'Nilai sistolik terlalu tinggi.',
        ],

        'map' => [
            'required' => 'Nilai MAP wajib diisi.',
            'decimal' => 'Nilai MAP harus berupa angka.',
        ],

        'diastolic' => [
            'required' => 'Nilai diastolik wajib diisi.',
            'decimal' => 'Nilai diastolik harus berupa angka.',
        ],

        'bpm' => [
            'required' => 'Nilai BPM wajib diisi.',
            'integer' => 'Nilai BPM harus berupa bilangan bulat.',
        ],

        'quality_status' => [
            'required' => 'Status kualitas wajib diisi.',
            'in_list' => 'Status kualitas tidak valid.',
        ],
    ];

    protected $skipValidation = false;

    protected $cleanValidationRules = true;

    /**
     * Menentukan kualitas hasil secara otomatis.
     */
    public function evaluateQuality(
        ?float $systolic,
        ?float $map,
        ?float $diastolic,
        ?int $bpm,
        ?int $beatCount = null,
        string $measurementType = 'both'
    ): array {
        $reasons = [];

        $hasBloodPressure =
            $systolic !== null
            && $map !== null
            && $diastolic !== null;

        $hasHeartRate =
            $bpm !== null;

        if (! $hasBloodPressure && ! $hasHeartRate) {
            return [
                'is_valid' => 0,
                'quality_status' => 'invalid',
                'failure_reason' => 'no_valid_measurement',
            ];
        }

        /*
         * Validasi teknis hanya dilakukan untuk
         * parameter yang benar-benar tersedia.
         */
        if ($hasBloodPressure) {
            if (! ($systolic > $map && $map > $diastolic)) {
                $reasons[] = 'sys_map_dia_order_invalid';
            }

            if ($systolic < 70 || $systolic > 250) {
                $reasons[] = 'systolic_out_of_range';
            }

            if ($map < 40 || $map > 200) {
                $reasons[] = 'map_out_of_range';
            }

            if ($diastolic < 40 || $diastolic > 150) {
                $reasons[] = 'diastolic_out_of_range';
            }

            if ($beatCount !== null && $beatCount < 5) {
                $reasons[] = 'insufficient_beats';
            }
        }

        if ($hasHeartRate) {
            if ($bpm < 40 || $bpm > 200) {
                $reasons[] = 'bpm_out_of_range';
            }
        }

        if ($reasons !== []) {
            return [
                'is_valid' => 0,
                'quality_status' => 'invalid',
                'failure_reason' => implode(',', $reasons),
            ];
        }

        $warning = false;
        $partialReason = null;

        /*
         * Warning medis hanya untuk parameter
         * yang tersedia, bukan diagnosis.
         */
        if (
            $hasBloodPressure
            && (
                $systolic >= 140
                || $diastolic >= 90
                || $systolic < 90
                || $diastolic < 60
            )
        ) {
            $warning = true;
        }

        if (
            $hasHeartRate
            && ($bpm < 60 || $bpm > 100)
        ) {
            $warning = true;
        }

        /*
         * Jika user memilih "both" namun hanya
         * satu kelompok parameter berhasil,
         * simpan hasil yang valid sebagai partial result.
         */
        if ($measurementType === 'both') {
            if (! $hasBloodPressure && $hasHeartRate) {
                $warning = true;
                $partialReason =
                    'partial_result_blood_pressure_unavailable';
            } elseif ($hasBloodPressure && ! $hasHeartRate) {
                $warning = true;
                $partialReason =
                    'partial_result_bpm_unavailable';
            }
        }

        return [
            'is_valid' => 1,
            'quality_status' =>
            $warning ? 'warning' : 'valid',
            'failure_reason' => $partialReason,
        ];
    }

    /**
     * Menyimpan pengukuran dan menyelesaikan request.
     */
    public function saveMeasurement(
        array $measurementData,
        ?int $requestId = null
    ): array {
        $requestModel = new MeasurementRequestModel();

        $request = null;
        $measurementType = 'both';

        if ($requestId !== null) {
            $request = $requestModel->find($requestId);

            if ($request === null) {
                return [
                    'success' => false,
                    'message' => 'Request pengukuran tidak ditemukan.',
                    'measurement_id' => null,
                ];
            }

            if ($request['status'] === 'completed') {
                return [
                    'success' => false,
                    'message' => 'Request pengukuran sudah selesai.',
                    'measurement_id' => null,
                ];
            }

            $measurementType =
                (string) (
                    $request['measurement_type']
                    ?? 'both'
                );
        }

        foreach (
            ['patient_id', 'device_record_id']
            as $field
        ) {
            if (! array_key_exists($field, $measurementData)) {
                return [
                    'success' => false,
                    'message' => "Field {$field} wajib tersedia.",
                    'measurement_id' => null,
                ];
            }
        }

        $systolic =
            isset($measurementData['systolic'])
            && $measurementData['systolic'] !== ''
            ? (float) $measurementData['systolic']
            : null;

        $map =
            isset($measurementData['map'])
            && $measurementData['map'] !== ''
            ? (float) $measurementData['map']
            : null;

        $diastolic =
            isset($measurementData['diastolic'])
            && $measurementData['diastolic'] !== ''
            ? (float) $measurementData['diastolic']
            : null;

        $bpm =
            isset($measurementData['bpm'])
            && $measurementData['bpm'] !== ''
            ? (int) $measurementData['bpm']
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

        if ($hasAnyBloodPressure && ! $hasBloodPressure) {
            return [
                'success' => false,
                'message' => 'Data tekanan darah harus berisi SYS, MAP, dan DIA secara lengkap.',
                'measurement_id' => null,
            ];
        }

        if (
            $measurementType === 'heart_rate'
            && ! $hasHeartRate
        ) {
            return [
                'success' => false,
                'message' => 'Hasil denyut nadi wajib tersedia.',
                'measurement_id' => null,
            ];
        }

        if (
            $measurementType === 'blood_pressure'
            && ! $hasBloodPressure
        ) {
            return [
                'success' => false,
                'message' => 'Hasil tekanan darah wajib tersedia.',
                'measurement_id' => null,
            ];
        }

        if (
            $measurementType === 'both'
            && ! $hasBloodPressure
            && ! $hasHeartRate
        ) {
            return [
                'success' => false,
                'message' => 'Minimal satu hasil pengukuran harus valid.',
                'measurement_id' => null,
            ];
        }

        $beatCount =
            isset($measurementData['beat_count'])
            && $measurementData['beat_count'] !== ''
            ? (int) $measurementData['beat_count']
            : null;

        $quality = $this->evaluateQuality(
            $systolic,
            $map,
            $diastolic,
            $bpm,
            $beatCount,
            $measurementType
        );

        $insertData = [
            'request_id' => $requestId,
            'patient_id' =>
            (int) $measurementData['patient_id'],
            'device_record_id' =>
            (int) $measurementData['device_record_id'],
            'systolic' =>
            $systolic !== null
                ? round($systolic, 2)
                : null,
            'map' =>
            $map !== null
                ? round($map, 2)
                : null,
            'diastolic' =>
            $diastolic !== null
                ? round($diastolic, 2)
                : null,
            'bpm' => $bpm,
            'beat_count' => $beatCount,
            'pressure_baseline_adc'
            => $measurementData['pressure_baseline_adc']
                ?? null,
            'adc_max'
            => $measurementData['adc_max']
                ?? null,
            'adc_min'
            => $measurementData['adc_min']
                ?? null,
            'is_valid' => $quality['is_valid'],
            'quality_status' =>
            $quality['quality_status'],
            'failure_reason' =>
            $quality['failure_reason'],
            'measured_at'
            => $measurementData['measured_at']
                ?? date('Y-m-d H:i:s'),
        ];

        /*
         * Bila request tersedia, pastikan hasil berasal
         * dari pasien dan perangkat yang sama.
         */
        if ($request !== null) {
            if (
                (int) $request['patient_id']
                !== $insertData['patient_id']
                ||
                (int) $request['device_record_id']
                !== $insertData['device_record_id']
            ) {
                return [
                    'success' => false,
                    'message' => 'Data pasien atau perangkat tidak sesuai dengan request.',
                    'measurement_id' => null,
                ];
            }
        }

        $database = $this->db;

        $database->transBegin();

        try {
            $measurementId = $this->insert(
                $insertData,
                true
            );

            if ($measurementId === false) {
                $database->transRollback();

                return [
                    'success' => false,
                    'message' => 'Data pengukuran gagal disimpan.',
                    'errors' => $this->errors(),
                    'measurement_id' => null,
                ];
            }

            if ($requestId !== null) {
                $requestUpdated =
                    $requestModel->markCompleted($requestId);

                if (! $requestUpdated) {
                    $database->transRollback();

                    return [
                        'success' => false,
                        'message' => 'Status request gagal diperbarui.',
                        'measurement_id' => null,
                    ];
                }
            }

            if ($database->transStatus() === false) {
                $database->transRollback();

                return [
                    'success' => false,
                    'message' => 'Transaksi database gagal.',
                    'measurement_id' => null,
                ];
            }

            $database->transCommit();

            return [
                'success' => true,
                'message' => 'Hasil pengukuran berhasil disimpan.',
                'measurement_id' => (int) $measurementId,
                'quality_status' =>
                $quality['quality_status'],
                'is_valid' =>
                (bool) $quality['is_valid'],
                'failure_reason' =>
                $quality['failure_reason'],
            ];
        } catch (Throwable $exception) {
            $database->transRollback();

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pengukuran.',
                'error' => $exception->getMessage(),
                'measurement_id' => null,
            ];
        }
    }

    /**
     * Mengambil hasil terbaru pasien.
     */
    public function getLatestByPatientId(
        int $patientId,
        bool $validOnly = false
    ): ?array {
        $builder = $this
            ->where('patient_id', $patientId);

        if ($validOnly) {
            $builder->where('is_valid', 1);
        }

        return $builder
            ->orderBy('measured_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Mengambil hasil terbaru berdasarkan Device ID.
     */
    public function getLatestByDeviceId(
        string $deviceId,
        bool $validOnly = false
    ): ?array {
        $normalizedDeviceId = strtoupper(
            str_replace(
                [':', '-', ' '],
                '',
                trim($deviceId)
            )
        );

        $builder = $this
            ->select([
                'measurements.*',
                'devices.device_id',
                'patients.patient_code',
                'patients.name AS patient_name',
            ])
            ->join(
                'devices',
                'devices.id = measurements.device_record_id',
                'inner'
            )
            ->join(
                'patients',
                'patients.id = measurements.patient_id',
                'inner'
            )
            ->where('devices.device_id', $normalizedDeviceId);

        if ($validOnly) {
            $builder->where('measurements.is_valid', 1);
        }

        return $builder
            ->orderBy('measurements.measured_at', 'DESC')
            ->orderBy('measurements.id', 'DESC')
            ->first();
    }

    /**
     * Riwayat pengukuran pasien.
     */
    public function getHistoryByPatientId(
        int $patientId,
        int $limit = 50,
        int $offset = 0,
        bool $validOnly = false
    ): array {
        if ($limit < 1) {
            $limit = 50;
        }

        if ($offset < 0) {
            $offset = 0;
        }

        $builder = $this
            ->where('patient_id', $patientId);

        if ($validOnly) {
            $builder->where('is_valid', 1);
        }

        return $builder
            ->orderBy('measured_at', 'DESC')
            ->findAll($limit, $offset);
    }

    /**
     * Riwayat berdasarkan rentang tanggal.
     */
    public function getHistoryByDateRange(
        int $patientId,
        string $startDate,
        string $endDate,
        bool $validOnly = false
    ): array {
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';

        $builder = $this
            ->where('patient_id', $patientId)
            ->where('measured_at >=', $startDateTime)
            ->where('measured_at <=', $endDateTime);

        if ($validOnly) {
            $builder->where('is_valid', 1);
        }

        return $builder
            ->orderBy('measured_at', 'ASC')
            ->findAll();
    }

    /**
     * Statistik pasien untuk dashboard/export.
     */
    public function getPatientStatistics(
        int $patientId,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $builder = $this->db
            ->table($this->table)
            ->select([
                'COUNT(id) AS total_measurements',
                'AVG(systolic) AS average_systolic',
                'AVG(map) AS average_map',
                'AVG(diastolic) AS average_diastolic',
                'AVG(bpm) AS average_bpm',
                'MAX(systolic) AS maximum_systolic',
                'MAX(diastolic) AS maximum_diastolic',
                'MAX(bpm) AS maximum_bpm',
                'MIN(systolic) AS minimum_systolic',
                'MIN(diastolic) AS minimum_diastolic',
                'MIN(bpm) AS minimum_bpm',
            ])
            ->where('patient_id', $patientId)
            ->where('is_valid', 1);

        if ($startDate !== null) {
            $builder->where(
                'measured_at >=',
                $startDate . ' 00:00:00'
            );
        }

        if ($endDate !== null) {
            $builder->where(
                'measured_at <=',
                $endDate . ' 23:59:59'
            );
        }

        $result = $builder
            ->get()
            ->getRowArray();

        if ($result === null) {
            return [
                'total_measurements' => 0,
                'average_systolic' => null,
                'average_map' => null,
                'average_diastolic' => null,
                'average_bpm' => null,
                'maximum_systolic' => null,
                'maximum_diastolic' => null,
                'maximum_bpm' => null,
                'minimum_systolic' => null,
                'minimum_diastolic' => null,
                'minimum_bpm' => null,
            ];
        }

        return $result;
    }

    /**
     * Mengambil hasil berdasarkan request.
     */
    public function findByRequestId(
        int $requestId
    ): ?array {
        return $this
            ->where('request_id', $requestId)
            ->first();
    }

    /**
     * Hitung hasil berdasarkan status kualitas.
     */
    public function countByQualityStatus(
        string $qualityStatus
    ): int {
        return $this
            ->where('quality_status', $qualityStatus)
            ->countAllResults();
    }
}
