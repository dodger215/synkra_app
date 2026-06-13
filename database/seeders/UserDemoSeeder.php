<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class UserDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure a Workspace/Tenant exists
        $tenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo'],
            [
                'name' => 'Demo Workspace',
                'settings' => []
            ]
        );

        // 2. Owner User (Full Access)
        User::firstOrCreate(
            ['email' => 'owner@synkra.test'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Sarah Owner',
                'password' => Hash::make('password'),
                'role' => UserRole::OWNER,
                'permissions' => [] // Owner bypasses explicit permissions via middleware
            ]
        );

        // 3. Manager User (High Access)
        User::firstOrCreate(
            ['email' => 'manager@synkra.test'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Mike Manager',
                'password' => Hash::make('password'),
                'role' => UserRole::MANAGER,
                'permissions' => [
                    'inventory' => ['view_products' => true, 'view_categories' => true, 'view_stock' => true],
                    'pos' => ['view_orders' => true, 'view_sessions' => true, 'open_session' => true],
                    'marketing' => ['view_campaigns' => true],
                    'supply_chain' => ['view_suppliers' => true]
                ]
            ]
        );

        // 4. Inventory Clerk User
        User::firstOrCreate(
            ['email' => 'clerk@synkra.test'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Alex Clerk',
                'password' => Hash::make('password'),
                'role' => UserRole::INVENTORY_CLERK,
                'permissions' => [
                    'inventory' => ['view_products' => true, 'view_stock' => true, 'manage_locations' => true, 'manage_bins' => true, 'view_stock_movements' => true],
                    'supply_chain' => ['view_receiving_reports' => true]
                ]
            ]
        );

        // 5. Cashier User
        User::firstOrCreate(
            ['email' => 'cashier@synkra.test'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Chris Cashier',
                'password' => Hash::make('password'),
                'role' => UserRole::CASHIER,
                'permissions' => [
                    'pos' => ['open_session' => true, 'view_orders' => true]
                ]
            ]
        );
    }
}
