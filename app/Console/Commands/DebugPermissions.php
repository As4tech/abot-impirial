<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DebugPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug permissions and roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Permissions Debug ===');
        
        // Check total permissions
        $totalPerms = Permission::count();
        $this->info("Total permissions in database: {$totalPerms}");
        
        // Check purchase permissions
        $purchasePerms = Permission::where('name', 'like', 'purchases.%')->get();
        $this->info("Purchase permissions found:");
        foreach ($purchasePerms as $perm) {
            $this->line("  - {$perm->name}");
        }
        
        // Check admin role
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $this->info("Admin role found with ID: {$admin->id}");
            $adminPermCount = $admin->permissions()->count();
            $this->info("Admin role has {$adminPermCount} permissions");
            
            // Check purchase permissions for admin
            $adminPurchasePerms = $admin->permissions()->where('name', 'like', 'purchases.%')->get();
            $this->info("Admin purchase permissions:");
            foreach ($adminPurchasePerms as $perm) {
                $this->line("  - {$perm->name}");
            }
        } else {
            $this->error("Admin role not found!");
        }
        
        return Command::SUCCESS;
    }
}
