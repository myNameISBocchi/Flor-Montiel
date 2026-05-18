<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Privilege;
use App\Models\RolePrivilege;

class AddEssentialPrivilegesToRolesSeeder extends Seeder
{
    public function run(): void
    {
        $essentialRoutes = [
            'auth.login',
            'peoples.show',
        ];
        
        $essentialPrivileges = Privilege::whereIn('route', $essentialRoutes)->get();
        
        if ($essentialPrivileges->isEmpty()) {
            $this->command->error('No se encontraron los privilegios esenciales. Ejecuta primero DynamicPrivilegesSeeder.');
            return;
        }
        
        $roles = Role::all();
        
        foreach ($roles as $role) {
            foreach ($essentialPrivileges as $privilege) {
                $exists = RolePrivilege::where('roleId', $role->id)
                    ->where('privilegeId', $privilege->id)
                    ->exists();
                    
                if (!$exists) {
                    RolePrivilege::create([
                        'roleId' => $role->id,
                        'privilegeId' => $privilege->id
                    ]);
                    $this->command->info("Asignado {$privilege->route} al rol {$role->roleName}");
                }
            }
        }
        
        $this->command->info('Privilegios esenciales asignados a todos los roles');
    }
}