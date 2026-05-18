<?php

namespace Database\Seeders;

use App\Models\Privilege;
use App\Models\Role;
use App\Models\RolePrivilege;
use Illuminate\Database\Seeder;

class RolePrivilegeSeeder extends Seeder
{
    public function run(): void
    {
        $this->verifyPrivilegesExist();
        $arrData = [
            'ADMINISTRADOR' => [
                'auth.login',
                'comunities.index', 'comunities.store', 'comunities.update', 'comunities.destroy', 'comunities.upload_photo',
                'councils.index', 'councils.by_comunity', 'councils.store', 'councils.update', 'councils.destroy',
                'committees.index', 'committees.subcommittees', 'committees.store', 'committees.update', 'committees.destroy',
                'peoples.index', 'peoples.search', 'peoples.show', 'peoples.store', 'peoples.update', 'peoples.destroy', 'peoples.upload_photo', 'peoples.assign_roles',
                'roles.index', 'roles.store', 'roles.update', 'roles.destroy',
                'privileges.index', 'privileges.store', 'privileges.update', 'privileges.destroy',
                'role_privileges.index', 'role_privileges.store',
                'countries.index', 'countries.store', 'countries.update', 'countries.destroy',
                'states.index', 'states.store', 'states.update', 'states.destroy',
                'cities.index', 'cities.store', 'cities.update', 'cities.destroy',
                'reports.voceros'
            ],
            'LIDER DE COMUNA' => [
                'auth.login',
                'comunities.index', 'comunities.store', 'comunities.update', 'comunities.upload_photo',
                'councils.index', 'councils.by_comunity', 'councils.store', 'councils.update',
                'committees.index', 'committees.subcommittees', 'committees.store', 'committees.update',
                'peoples.index', 'peoples.search', 'peoples.show', 'peoples.store', 'peoples.update', 'peoples.upload_photo',
                'reports.voceros'
            ],
            'VOCERO' => [
                'auth.login',
                'comunities.index',
                'councils.index', 'councils.by_comunity',
                'committees.index', 'committees.subcommittees',
                'peoples.show',
                'reports.voceros'
            ]
        ];

        foreach ($arrData as $roleName => $privilegeRoutes) {
            $role = Role::where('roleName', $roleName)->first();
            
            if (!$role) {
                $this->command->warn("Rol '{$roleName}' no encontrado, omitiendo...");
                continue;
            }
            
            RolePrivilege::where('roleId', $role->id)->delete();
            
            foreach ($privilegeRoutes as $route) {
                $privilege = Privilege::where('route', $route)->first();
                
                if (!$privilege) {
                    $this->command->warn("Privilegio con ruta '{$route}' no encontrado, omitiendo...");
                    continue;
                }
                
                RolePrivilege::create([
                    'roleId' => $role->id,
                    'privilegeId' => $privilege->id
                ]);
            }
            
            $this->command->info("Privilegios asignados al rol '{$roleName}'");
        }
    }

    private function verifyPrivilegesExist(): void
    {
        $requiredRoutes = [
            'auth.login',
            'comunities.index', 'comunities.store', 'comunities.update', 'comunities.destroy', 'comunities.upload_photo',
            'councils.index', 'councils.by_comunity', 'councils.store', 'councils.update', 'councils.destroy',
            'committees.index', 'committees.subcommittees', 'committees.store', 'committees.update', 'committees.destroy',
            'peoples.index', 'peoples.search', 'peoples.show', 'peoples.store', 'peoples.update', 'peoples.destroy', 'peoples.upload_photo', 'peoples.assign_roles',
            'roles.index', 'roles.store', 'roles.update', 'roles.destroy',
            'privileges.index', 'privileges.store', 'privileges.update', 'privileges.destroy',
            'role_privileges.index', 'role_privileges.store',
            'countries.index', 'countries.store', 'countries.update', 'countries.destroy',
            'states.index', 'states.store', 'states.update', 'states.destroy',
            'cities.index', 'cities.store', 'cities.update', 'cities.destroy',
            'reports.voceros'
        ];

        foreach ($requiredRoutes as $route) {
            $exists = Privilege::where('route', $route)->exists();
            if (!$exists) {
                $this->command->warn("Privilegio faltante: '{$route}'");
            }
        }
    }
}