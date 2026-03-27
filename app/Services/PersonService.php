<?php
namespace App\Services;
use App\Models\Person;
use App\Models\PersonCommittees;
use App\Models\PersonComunity;
use App\Models\PersonCouncil;
use App\Models\PersonRole;
use Illuminate\Support\Facades\Crypt;

class PersonService{
    public function store(array $person){
        $arrRoles = json_decode($person['roleId']);
        $idComunity = $person['comunityId'];
        $idCouncil = $person['councilId'];
        $idCommitte = $person['committeeId'];
         
        $duplicate = Person::select('id')->where([
            ['identification',$person['identification']],
            ['email',$person['email']],
            ['phone',$person['phone']]])->first();
        if($duplicate){
            return false;
        }else{
            $countryDecrypted = Crypt::decrypt($person['cityId']);
            $person['cityId'] = $countryDecrypted;
            $newPerson = Person::create($person);
            
            PersonRole::where('personId', $newPerson->id)->delete();
            $rolesInsert = [];

            for($i = 0; $i < count($arrRoles); $i++){
                $roleIdDecrypted = Crypt::decrypt($arrRoles[$i]);
                $rolesInsert[] =[
                    'personId' => $newPerson->id,
                    'roleId' => $roleIdDecrypted,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if(!empty($rolesInsert)){
                PersonRole::insert($rolesInsert);
            }
            if(!empty($idComunity)){
                PersonComunity::create([
                    'personId' => $newPerson->id,
                    'comunityId' => Crypt::decrypt($idComunity)
                ]);
            }

            if(!empty($idCouncil)){
                PersonCouncil::create([
                    'personId' => $newPerson->id,
                    'councilId' => Crypt::decrypt($idCouncil)
                ]);
            }
            if(!empty($idCommitte)){
                PersonCommittees::create(
                    [
                        'personId' => $newPerson->id,
                        'committeeId' => Crypt::decrypt($idCommitte)
                    ]
                );
            }
            
            return true;
        }

    }
    public function findAll(){
        $all = Person::select(
            'peoples.id as personId',
            'firstName',
            'lastName',
            'identification',
            'phone',
            'status',
            'date',
            'cityName as city',
            'peoples.cityId as cityId',
            'countries.countryName as country',
            'states.stateName as state',
            'photoPerson',
            'comunities.comunityName',
            'councils.councilName',
            'committees.committeeName',
            'roles.roleName'
        )->join('cities', 'peoples.cityId', '=', 'cities.id'
        )->join('states', 'cities.stateId', '=', 'states.id'
        )->join('countries','states.countryId','=','countries.id'
        )->join(
            'peoples_comunities', 'peoples.id', '=', 'peoples_comunities.personId'
        )->join(
            'comunities', 'peoples_comunities.comunityId', '=', 'comunities.id'
        )->join(
            'peoples_councils', 'peoples.id', '=', 'peoples_councils.personId'
        )->join(
            'councils', 'peoples_councils.councilId', '=', 'councils.id'
        )->join(
            'peoples_committees', 'peoples.id', '=', 'peoples_committees.personId'
        )->join(
            'committees', 'peoples_committees.committeeId', '=', 'committees.id'
        )->join(
            'peoples_roles', 'peoples.id', '=', 'peoples_roles.personId'
        )->join(
            'roles', 'peoples_roles.roleId', 'roles.id'
        )->get()->map(function($item){
            $blockedResult = PersonRole::select('id')->where('personId', '=', $item->personId)->first();
            if($blockedResult){
                $item->blocked = 1;
            }else{
                $item->blocked = 0;
            }
            $item->locality = [
                'city' => $item->city,
                'country' => $item->country,
                'state' => $item->state
            ];
            $personIdEncrypt = Crypt::encrypt($item->personId);
            $cityIdEncrypt = Crypt::encrypt($item->cityId);
            unset($item->city);
            unset($item->country);
            unset($item->state);
            unset($item->personId);
            unset($item->cityId);
            $item->personId = $personIdEncrypt;
            $item->cityId = $cityIdEncrypt;
            return $item;
        });
        return $all;

    }
}



?>