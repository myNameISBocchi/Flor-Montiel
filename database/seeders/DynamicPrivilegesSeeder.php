<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DynamicPrivilegesSeeder extends Seeder
{
    public function run(): void
    {
        $privileges = [
         
            ['privilegeName' => 'Iniciar Sesión', 'route' => 'auth.login', 'status' => 1],
            
            ['privilegeName' => 'Ver Comunidades', 'route' => 'comunities.index', 'status' => 1],
            ['privilegeName' => 'Crear Comunidad', 'route' => 'comunities.store', 'status' => 1],
            ['privilegeName' => 'Editar Comunidad', 'route' => 'comunities.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Comunidad', 'route' => 'comunities.destroy', 'status' => 1],
            ['privilegeName' => 'Subir Foto Comunidad', 'route' => 'comunities.upload_photo', 'status' => 1],
            
            
            ['privilegeName' => 'Ver Consejos', 'route' => 'councils.index', 'status' => 1],
            ['privilegeName' => 'Ver Consejos por Comunidad', 'route' => 'councils.by_comunity', 'status' => 1],
            ['privilegeName' => 'Crear Consejo', 'route' => 'councils.store', 'status' => 1],
            ['privilegeName' => 'Editar Consejo', 'route' => 'councils.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Consejo', 'route' => 'councils.destroy', 'status' => 1],
            
            
            ['privilegeName' => 'Ver Comités', 'route' => 'committees.index', 'status' => 1],
            ['privilegeName' => 'Ver Subcomités', 'route' => 'committees.subcommittees', 'status' => 1],
            ['privilegeName' => 'Crear Comité', 'route' => 'committees.store', 'status' => 1],
            ['privilegeName' => 'Editar Comité', 'route' => 'committees.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Comité', 'route' => 'committees.destroy', 'status' => 1],
            
          
            ['privilegeName' => 'Ver Personas', 'route' => 'peoples.index', 'status' => 1],
            ['privilegeName' => 'Buscar Personas', 'route' => 'peoples.search', 'status' => 1],
            ['privilegeName' => 'Ver Detalle Persona', 'route' => 'peoples.show', 'status' => 1],
            ['privilegeName' => 'Crear Persona', 'route' => 'peoples.store', 'status' => 1],
            ['privilegeName' => 'Editar Persona', 'route' => 'peoples.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Persona', 'route' => 'peoples.destroy', 'status' => 1],
            ['privilegeName' => 'Subir Foto Persona', 'route' => 'peoples.upload_photo', 'status' => 1],
            ['privilegeName' => 'Asignar Roles a Persona', 'route' => 'peoples.assign_roles', 'status' => 1],
            
            
            ['privilegeName' => 'Ver Roles', 'route' => 'roles.index', 'status' => 1],
            ['privilegeName' => 'Crear Rol', 'route' => 'roles.store', 'status' => 1],
            ['privilegeName' => 'Editar Rol', 'route' => 'roles.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Rol', 'route' => 'roles.destroy', 'status' => 1],
            
           
            ['privilegeName' => 'Ver Privilegios', 'route' => 'privileges.index', 'status' => 1],
            ['privilegeName' => 'Crear Privilegio', 'route' => 'privileges.store', 'status' => 1],
            ['privilegeName' => 'Editar Privilegio', 'route' => 'privileges.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Privilegio', 'route' => 'privileges.destroy', 'status' => 1],
            

            ['privilegeName' => 'Ver Privilegios de Rol', 'route' => 'role_privileges.index', 'status' => 1],
            ['privilegeName' => 'Asignar Privilegios a Rol', 'route' => 'role_privileges.store', 'status' => 1],
            
           
            ['privilegeName' => 'Ver Países', 'route' => 'countries.index', 'status' => 1],
            ['privilegeName' => 'Crear País', 'route' => 'countries.store', 'status' => 1],
            ['privilegeName' => 'Editar País', 'route' => 'countries.update', 'status' => 1],
            ['privilegeName' => 'Eliminar País', 'route' => 'countries.destroy', 'status' => 1],
            
            ['privilegeName' => 'Ver Estados', 'route' => 'states.index', 'status' => 1],
            ['privilegeName' => 'Crear Estado', 'route' => 'states.store', 'status' => 1],
            ['privilegeName' => 'Editar Estado', 'route' => 'states.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Estado', 'route' => 'states.destroy', 'status' => 1],
            
            ['privilegeName' => 'Ver Ciudades', 'route' => 'cities.index', 'status' => 1],
            ['privilegeName' => 'Crear Ciudad', 'route' => 'cities.store', 'status' => 1],
            ['privilegeName' => 'Editar Ciudad', 'route' => 'cities.update', 'status' => 1],
            ['privilegeName' => 'Eliminar Ciudad', 'route' => 'cities.destroy', 'status' => 1],
            
           
            ['privilegeName' => 'Generar Reporte Voceros', 'route' => 'reports.voceros', 'status' => 1],
        ];
        
        foreach ($privileges as $privilege) {
            DB::table('privileges')->updateOrInsert(
                ['route' => $privilege['route']],
                [
                    'privilegeName' => $privilege['privilegeName'],
                    'status' => $privilege['status']
                ]
            );
        }
        
        $this->command->info('Privilegios creados/actualizados correctamente');
    }
}