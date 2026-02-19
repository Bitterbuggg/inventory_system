<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function userRepository(bool $getShared = true): \App\Repositories\Contracts\UserRepositoryInterface
    {
        if ($getShared) {
            return static::getSharedInstance('userRepository');
        }

        return new \App\Repositories\Database\UserRepository(new \App\Models\UserModel());
    }

    public static function roleRepository(bool $getShared = true): \App\Repositories\Contracts\RoleRepositoryInterface
    {
        if ($getShared) {
            return static::getSharedInstance('roleRepository');
        }

        return new \App\Repositories\Database\RoleRepository(new \App\Models\RoleModel());
    }

    public static function purchaseWorkflowRepository(bool $getShared = true): \App\Repositories\Contracts\PurchaseWorkflowRepositoryInterface
    {
        if ($getShared) {
            return static::getSharedInstance('purchaseWorkflowRepository');
        }

        $db = \Config\Database::connect();

        return new \App\Repositories\Database\PurchaseWorkflowRepository(
            $db,
            new \App\Models\PurchaseRequestModel(),
            new \App\Models\PurchaseRequestItemModel(),
            new \App\Models\ApprovalModel(),
            new \App\Models\PurchaseOrderModel(),
            new \App\Models\PurchaseOrderItemModel(),
            new \App\Models\PoRequestModel(),
            new \App\Models\ReceivingModel(),
            new \App\Models\ReceivingItemModel(),
            new \App\Models\IssuanceModel(),
            new \App\Models\IssuanceItemModel(),
        );
    }

    public static function inventoryRepository(bool $getShared = true): \App\Repositories\Contracts\InventoryRepositoryInterface
    {
        if ($getShared) {
            return static::getSharedInstance('inventoryRepository');
        }

        return new \App\Repositories\Database\InventoryRepository(
            new \App\Models\InventoryStockModel(),
            new \App\Models\InventoryMovementModel(),
        );
    }

    public static function authService(bool $getShared = true): \App\Services\AuthService
    {
        if ($getShared) {
            return static::getSharedInstance('authService');
        }

        return new \App\Services\AuthService(
            static::userRepository(),
            static::roleRepository(),
        );
    }

    public static function purchaseWorkflowService(bool $getShared = true): \App\Services\PurchaseWorkflowService
    {
        if ($getShared) {
            return static::getSharedInstance('purchaseWorkflowService');
        }

        return new \App\Services\PurchaseWorkflowService(
            static::purchaseWorkflowRepository(),
            static::inventoryRepository(),
        );
    }
}
