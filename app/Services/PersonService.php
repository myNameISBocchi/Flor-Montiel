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
         
        $duplicate = Person::select('id')->where([['identification',$person['identification']],['email',$person['email']],['phone',$person['phone']],])->first();
        if($duplicate){
            return false;
        }else{
            $countryDecrypted = Crypt::decrypt($person['cityId']);
            $person['cityId'] = $countryDecrypted;
            $person = Person::create($person);
            
            PersonRole::where('personId', $person->id)->delete();
            $rolesInsert = [];

            for($i = 0; $i < count($arrRoles); $i++){
                $roleIdDecrypted = Crypt::decrypt($arrRoles[$i]);
                $rolesInsert[] =[
                    'personId' => $person->id,
                    'roleId' => $roleIdDecrypted,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if(!empty($rolesInsert)){
                PersonRole::insert($rolesInsert);
            }
            if(!empty($person['comunityId'])){
                PersonComunity::create([
                    'personId' => $person->id,
                    'comunityId' => Crypt::decrypt($person['comunityId'])
                ]);
            }

            if(!empty($person['councilId'])){
                PersonCouncil::create([
                    'personId' => $person->id,
                    'councilId' => Crypt::decrypt($person['councilId'])
                ]);
            }
            if(!empty($person['committeeId'])){
                PersonCommittees::create(
                    [
                        'personId' => $person->id,
                        'committeeId' => Crypt::decrypt($person['committeeId'])
                    ]
                );
            }
            
            return true;
        }

    }
    public function findAll(){
        $all = Person::select(
            'person.id as personId',
            'firstName',
            'lastName',
            'identification',
            'phone',
            'status',
            'date',
            'cityName as city',
            'person.cityId as cityId',
            'countries.countryName as country',
            'states.stateName as state',
            'photoPerson'
        )->join('cities', 'person.cityId', '=', 'cities.id'
        )->join('states', 'person.stateId', '=', 'states.id'
        )->join('countries','person.countryId','=','countries.id')->get()->map(function($item){
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