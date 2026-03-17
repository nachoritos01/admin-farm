<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (idempotent)
        $permissions = [
            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            // Production
            'production.view',
            'production.mark',
            'production.create',
            'production.edit',
            // Products & Customers
            'products.manage',
            'customers.manage',
            // Payments
            'payments.view',
            'payments.create',
            // Pricing & Reports
            'pricing.manage',
            'reports.export',
            // Settings & Admin
            'settings.manage',
            'users.manage',
            'billing.manage',
            'plugins.manage',
            // Hen Batches
            'hen_batches.view',
            'hen_batches.manage',
            // Suppliers
            'suppliers.view',
            'suppliers.manage',
            // Expenses
            'expenses.view',
            'expenses.create',
            'expenses.manage',
            // Shipments
            'shipments.view',
            'shipments.manage',
            'shipments.update_status',
            // Inventory
            'inventory.view',
            // Health Records
            'health_records.view',
            'health_records.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles with permissions (idempotent)
        $roles = [
            'owner' => $permissions, // All permissions
            'admin' => array_filter($permissions, fn ($p) => $p !== 'billing.manage'),
            'ventas' => [
                'orders.view', 'orders.create', 'orders.edit',
                'customers.manage',
                'payments.view', 'payments.create',
                'shipments.view', 'shipments.manage', 'shipments.update_status',
                'inventory.view',
            ],
            'produccion' => [
                'orders.view',
                'production.view', 'production.mark', 'production.create', 'production.edit',
                'hen_batches.view',
                'health_records.view',
                'inventory.view',
            ],
            'contabilidad' => [
                'orders.view',
                'payments.view',
                'reports.export',
                'suppliers.view',
                'expenses.view', 'expenses.create',
            ],
            'chofer' => [
                'orders.view',
                'shipments.view', 'shipments.update_status',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
