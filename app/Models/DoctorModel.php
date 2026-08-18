<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table = 'doctors';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'doctor_code',
        'name',
        'specialization',
        'phone',
        'email',
        'practice_location',
        'status',
    ];

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'doctor_code' => [
            'label' => 'ID Dokter',
            'rules' => 'required|max_length[30]',
        ],

        'name' => [
            'label' => 'Nama dokter',
            'rules' => 'required|max_length[100]',
        ],

        'status' => [
            'label' => 'Status dokter',
            'rules' => 'required|in_list[active,inactive]',
        ],
    ];

    /**
     * Merapikan ID dokter sebelum pencarian.
     */
    public function normalizeDoctorCode(
        string $doctorCode
    ): string {
        return strtoupper(
            trim($doctorCode)
        );
    }

    /**
     * Mencari dokter berdasarkan doctor_code.
     */
    public function findByDoctorCode(
        string $doctorCode
    ): ?array {
        return $this
            ->where(
                'doctor_code',
                $this->normalizeDoctorCode(
                    $doctorCode
                )
            )
            ->first();
    }

    /**
     * Mencari dokter aktif berdasarkan doctor_code.
     *
     * Method ini digunakan oleh
     * IdentificationController.
     */
    public function findActiveByDoctorCode(
        string $doctorCode
    ): ?array {
        return $this
            ->where(
                'doctor_code',
                $this->normalizeDoctorCode(
                    $doctorCode
                )
            )
            ->where('status', 'active')
            ->first();
    }
}
