<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [

        'recipient_role',
        'recipient_id',

        'title',
        'message',

        'type',

        'reference_type',
        'reference_id',

        'action_url',

        'is_read',
        'read_at'
    ];

    protected $useTimestamps = false;

    //------------------------------------------------

    public function unreadCount(string $role, int $recipientId)
    {
        return $this
            ->where('recipient_role', $role)
            ->where('recipient_id', $recipientId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    //------------------------------------------------

    public function latest(string $role, int $recipientId, int $limit = 10)
    {
        return $this

            ->where('recipient_role', $role)

            ->where('recipient_id', $recipientId)

            ->orderBy('created_at', 'DESC')

            ->findAll($limit);
    }

    //------------------------------------------------

    public function markAsRead(int $id)
    {
        return $this->update($id, [

            'is_read' => 1,

            'read_at' => date('Y-m-d H:i:s')

        ]);
    }

    //------------------------------------------------

    public function createNotification(array $data)
    {
        return $this->insert($data);
    }
}
