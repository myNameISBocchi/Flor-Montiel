<?php
namespace App\Services;
use App\Models\Person;
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
            $arrInsert = [];

            for($i = 0; $i < count($arrRoles); $i++){
                $roleIdDecrypted = Crypt::decrypt($arrRoles[$i]);
                $arrInsert[] =[
                    'personId' => $person->id,
                    'roleId' => $roleIdDecrypted
                ];
            }
            PersonRole::insert($arrInsert);
            return true;
        }

    }
    public function findAll(){

    }
}



?>