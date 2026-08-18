<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsultationRequestModel extends Model
{
    protected $table = 'consultation_requests';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'patient_id',
        'doctor_id',
        'message',
        'preferred_platform',
        'preferred_date',
        'preferred_time',
        'scheduled_at',
        'status',
        'meeting_link',
        'doctor_response',
        'responded_at',
        'completed_at',
    ];

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'patient_id' => [
            'label' => 'Pasien',
            'rules' => 'required|is_natural_no_zero',
        ],

        'doctor_id' => [
            'label' => 'Dokter',
            'rules' => 'required|is_natural_no_zero',
        ],

        'message' => [
            'label' => 'Pesan konsultasi',
            'rules' => 'required',
        ],

        'preferred_platform' => [
            'label' => 'Platform',
            'rules' => 'required|in_list[google_meet,zoom,whatsapp,other]',
        ],

        'preferred_date' => [
            'label' => 'Tanggal pilihan',
            'rules' => 'permit_empty|valid_date[Y-m-d]',
        ],

        'preferred_time' => [
            'label' => 'Waktu pilihan',
            'rules' => 'permit_empty',
        ],

        'status' => [
            'label' => 'Status',
            'rules' => 'required|in_list[pending,accepted,rejected,rescheduled,completed,cancelled]',
        ],
    ];

    public function getByPatientId(
        int $patientId,
        int $limit = 20
    ): array {
        return $this
            ->select([
                'consultation_requests.*',
                'doctors.name AS doctor_name',
                'doctors.doctor_code',
                'doctors.specialization',
            ])
            ->join(
                'doctors',
                'doctors.id = consultation_requests.doctor_id',
                'inner'
            )
            ->where(
                'consultation_requests.patient_id',
                $patientId
            )
            ->orderBy(
                'consultation_requests.created_at',
                'DESC'
            )
            ->findAll($limit);
    }

    public function getByDoctorId(
        int $doctorId,
        int $limit = 50
    ): array {
        return $this
            ->select([
                'consultation_requests.*',
                'patients.name AS patient_name',
                'patients.patient_code',
                'patients.phone AS patient_phone',
            ])
            ->join(
                'patients',
                'patients.id = consultation_requests.patient_id',
                'inner'
            )
            ->where(
                'consultation_requests.doctor_id',
                $doctorId
            )
            ->orderBy(
                'consultation_requests.created_at',
                'DESC'
            )
            ->findAll($limit);
    }
}
