<?php

namespace App\Services;

use App\Models\AuditLogModel;

class AuditService
{
    protected $auditModel;

    public function __construct()
    {
        $this->auditModel = new AuditLogModel();
    }

    /**
     * Log a create action
     */
    public function logCreate(string $entityType, int $entityId, array $data, ?string $description = null): void
    {
        $this->auditModel->logChange(
            $entityType,
            $entityId,
            'CREATE',
            null,
            $data,
            $description ?? "{$entityType} created"
        );
    }

    /**
     * Log an update action
     */
    public function logUpdate(string $entityType, int $entityId, array $oldValues, array $newValues, ?string $description = null): void
    {
        $this->auditModel->logChange(
            $entityType,
            $entityId,
            'UPDATE',
            $oldValues,
            $newValues,
            $description ?? "{$entityType} updated"
        );
    }

    /**
     * Log a delete action
     */
    public function logDelete(string $entityType, int $entityId, array $data, ?string $description = null): void
    {
        $this->auditModel->logChange(
            $entityType,
            $entityId,
            'DELETE',
            $data,
            null,
            $description ?? "{$entityType} deleted"
        );
    }

    /**
     * Log a custom action
     */
    public function logAction(string $entityType, int $entityId, string $action, ?array $oldValues = null, ?array $newValues = null, ?string $description = null): void
    {
        $this->auditModel->logChange(
            $entityType,
            $entityId,
            $action,
            $oldValues,
            $newValues,
            $description
        );
    }

    /**
     * Get audit trail for an entity
     */
    public function getEntityHistory(string $entityType, int $entityId)
    {
        return $this->auditModel->getEntityTrail($entityType, $entityId);
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity(int $limit = 50)
    {
        return $this->auditModel->getRecent($limit);
    }
}
