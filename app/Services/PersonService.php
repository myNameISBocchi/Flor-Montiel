<?php
namespace App\Services;
use App\Models\Person;
use App\Models\PersonComunity;
use App\Models\PersonCouncil;
use App\Models\PersonRole;
use Illuminate\Support\Facades\Crypt;

class PersonService{
    public function store(array $person){
        $arrRoles = json_decode($person['roleId']);
        $arrCommittee = json_decode($person['committeeId']);
         $arrComunities = json_decode($person['comunityId']);
         $arrCouncils = json_decode($person['councilId']);
         
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
            
            PersonComunity::where('personId', '=', $person->id)->delete();
            $insertComunities = [];
            for($i = 0; $i < count($arrComunities); $i++){
                $ComunityDecrypted = Crypt::decrypt($arrComunities[$i]);
                $insertComunities[] = [
                    'personId' => $person->id,
                    'comunityId' => $ComunityDecrypted,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if(!empty($insertComunities)){
                PersonComunity::insert($insertComunities);
            }
            PersonCouncil::where('personId', '=', $person->id)->delete();
            $insertCouncils = [];
            for($i = 0;  $i < count($arrCouncils); $i++){
                $councilDecrypted = Crypt::decrypt($arrCouncils[$i]);
                $insertCouncils[] = [
                    'personId' => $person->id,
                    'councilId' => $councilDecrypted,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if(!empty($insertCouncils)){
                PersonCouncil::insert($insertCouncils);
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