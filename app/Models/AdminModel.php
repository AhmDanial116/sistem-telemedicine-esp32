<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admins';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'admin_code',
        'name',
        'phone',
        'status',
    ];

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    public function normalizeAdminCode(
        string $adminCode
    ): string {
        return strtoupper(
            trim($adminCode)
        );
    }

    public function findActiveByAdminCode(
        string $adminCode
    ): ?array {
        return $this
            ->where(
                'admin_code',
                $this->normalizeAdminCode(
                    $adminCode
                )
            )
            ->where('status', 'active')
            ->first();
    }
}
