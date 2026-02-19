<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'user_id',
        'entity_type',
        'entity_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
        'created_at',
    ];

    /**
     * Log an entity change
     */
    public function logChange(
        string $entityType,
        int $entityId,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): void {
        $request = service('request');
        $userId = session()->get('auth_user')['id'] ?? null;

        $this->insert([
            'user_id' => $userId,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => strtoupper($action),
            'old_values' => !empty($oldValues) ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
            'new_values' => !empty($newValues) ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->toString(),
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Log to application log as well
        $logMessage = "🔍 [{$action}] {$entityType}#{$entityId}";
        if ($description) {
            $logMessage .= " - {$description}";
        }
        log_message('info', $logMessage);
    }

    /**
     * Get audit trail for an entity
     */
    public function getEntityTrail(string $entityType, int $entityId)
    {
        return $this->where([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ])
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get recent changes
     */
    public function getRecent(int $limit = 50)
    {
        return $this->select('audit_logs.*, users.full_name, users.email')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->orderBy('audit_logs.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
