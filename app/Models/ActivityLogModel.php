<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'actor_role',
        'actor_id',
        'action',
        'description',
        'entity_type',
        'entity_id',
        'ip_address',
    ];

    protected $useTimestamps = false;

    public function record(
        string $actorRole,
        ?int $actorId,
        string $action,
        ?string $description = null,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $ipAddress = null
    ): bool {
        return $this->insert([
            'actor_role' => $actorRole,
            'actor_id' => $actorId,
            'action' => $action,
            'description' => $description,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => $ipAddress,
        ]) !== false;
    }
}
