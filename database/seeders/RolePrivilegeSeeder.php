<?php

namespace Database\Seeders;

use App\Models\Privilege;
use App\Models\Role;
use App\Models\RolePrivilege;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrData = [
            [
                'roleName' => 'ADMINISTRADOR',
                'privileges' => [
                    'users',
                    'comunities',
                    'councils',
                    'committees',
                    'roles',
                    'countries',
                    'states',
                    'cities'

                ]
            ],

            [
                'roleName' => 'LIDER DE COMUNA',
                'privileges' => [
                    'comunities',
                    'councils',
                    'committees',
                    'users'
                ]

            ],

            [
                'roleName' => 'VOCERO',
                'privileges' => []

            ]
        ];

        foreach($arrData as $key => $rolePrivilege){
            $arrInsert = [];
            $role = Role::where('roleName',$rolePrivilege['roleName'])->first();
            foreach($rolePrivilege['privileges'] as $privilegeData){
                $privilege = Privilege::where('route', '=', $privilegeData)->first();
                $now = now();
                $arrInsert[]=[
                    'roleId' => $role->id,
                    'privilegeId' => $privilege->id,
                    'created_at' => $now,
                    'updated_at' => $now

                ]; 
            }
            RolePrivilege::insert($arrInsert);

        }
    }
}
