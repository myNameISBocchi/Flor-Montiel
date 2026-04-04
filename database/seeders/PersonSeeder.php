<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\Comunity;
use App\Models\Council;
use App\Models\Person;
use App\Models\PersonCommittees;
use App\Models\PersonComunity;
use App\Models\PersonCouncil;
use App\Models\PersonRole;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrPerson = [
            [
                'firstName' => 'Alfonso',
                'lastName' => 'Espina',
                'email' => 'espinaalfonso123@gmail.com',
                'phone' => '0414-651-0323',
                'identification' => '31478490',
                'status' => 1,
                'date' => '05-10-05',
                'password' => 'jesus',
                'cityId' => 1,
                'roles' => [
                    'ADMINISTRADOR'
                ],
                'comunity' => 'CACHIRI',
                'committe' => 'ADMINISTRATIVA',
                'council' => 'SAN MAGALLAN'
            ]
        ];
        foreach($arrPerson as $key => $data){
            $comunities = $data['comunity'];
            $committes = $data['committe'];
            $council = $data['council'];
            $rolesNames = $data['roles'];

            unset($data['roles'], $data['comunity'], $data['council'], $data['committe']);

            $person = Person::create($data);
            foreach($rolesNames as $key => $roleName){
                $roles = Role::where('roleName', $roleName)->first();
                if($roles){
                    PersonRole::create(
                        [
                            'personId' => $person->id,
                            'roleId' => $roles->id
                        ]
                    );
                }
            }
            $findComunity = Comunity::where('comunityName',$comunities)->first();
            if($findComunity){
                PersonComunity::create([
                    'personId' => $person->id,
                    'comunityId' => $findComunity->id
                ]);
            }
            $findCouncil = Council::where('councilName', $council)->first();
            if($findCouncil){
                PersonCouncil::create([
                    'personId' => $person->id,
                    'councilId' => $findCouncil->id
                ]);
            }
            $findCommitte = Committee::where('committeeName', $committes)->first();
            if($findCommitte){
                PersonCommittees::create([
                    'personId' => $person->id,
                    'committeeId' => $findCommitte->id
                ]);

            }
        


        }
    
    }
}
