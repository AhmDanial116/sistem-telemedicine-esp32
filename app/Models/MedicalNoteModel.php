<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalNoteModel extends Model
{
    protected $table = 'medical_notes';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'doctor_id',
        'patient_id',
        'measurement_id',
        'title',
        'assessment',
        'recommendation',
        'priority',
        'is_read_by_patient',
        'read_at',
    ];

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'doctor_id' => [
            'label' => 'Dokter',
            'rules' => 'required|is_natural_no_zero',
        ],

        'patient_id' => [
            'label' => 'Pasien',
            'rules' => 'required|is_natural_no_zero',
        ],

        'measurement_id' => [
            'label' => 'Hasil pengukuran',
            'rules' => 'permit_empty|is_natural_no_zero',
        ],

        'title' => [
            'label' => 'Judul',
            'rules' => 'permit_empty|max_length[150]',
        ],

        'assessment' => [
            'label' => 'Penilaian',
            'rules' => 'permit_empty',
        ],

        'recommendation' => [
            'label' => 'Rekomendasi',
            'rules' => 'required',
        ],

        'priority' => [
            'label' => 'Prioritas',
            'rules' => 'required|in_list[normal,attention,urgent]',
        ],
    ];

    public function getByPatientId(
        int $patientId,
        int $limit = 20
    ): array {
        return $this
            ->select([
                'medical_notes.*',
                'doctors.name AS doctor_name',
                'doctors.doctor_code',
                'measurements.systolic',
                'measurements.diastolic',
                'measurements.map',
                'measurements.bpm',
                'measurements.measured_at',
            ])
            ->join(
                'doctors',
                'doctors.id = medical_notes.doctor_id',
                'inner'
            )
            ->join(
                'measurements',
                'measurements.id = medical_notes.measurement_id',
                'left'
            )
            ->where(
                'medical_notes.patient_id',
                $patientId
            )
            ->orderBy(
                'medical_notes.created_at',
                'DESC'
            )
            ->findAll($limit);
    }
}
