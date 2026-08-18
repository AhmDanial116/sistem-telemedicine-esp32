<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorAssignmentModel extends Model
{
    protected $table = 'doctor_assignments';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'doctor_id',
        'patient_id',
        'assigned_by',
        'assigned_at',
        'ended_at',
        'status'
    ];
}
