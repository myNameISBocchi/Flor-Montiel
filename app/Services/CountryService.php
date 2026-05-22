<?php
namespace App\Services;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Crypt;
class CountryService{
    public function store(array $country){
        $duplicate = Country::select('id')->where('countryName', '=', $country['countryName'])->first();
        if($duplicate){
            return false;
        }else{
            return Country::create($country);
        }
    }
    
    public function findAll(){
        $findAll = Country::select('id', 'countryName')->get()->map(function($item){
            $used = State::where('countryId', '=', $item->id)->first();
            $idEncrypted = Crypt::encrypt($item->id);
            $item->countryId = $idEncrypted;
            if($used){
                $item->blocked = 1;
            }else{
                $item->blocked = 0;
            }
            unset($item->id);
            
            
            return $item;
        });
        return $findAll;
    }

    public function update(string $id, array $country){
        $idDcrypted = Crypt::decrypt($id);
        $duplicate = Country::select('id')->where([
            ['id', '!=', $idDcrypted],
            ['countryName', '=', $country['countryName']],
        ])->first();
        if($duplicate){
            return false;
        }else{
            return Country::where('id', '=', $idDcrypted)->update($country);
        }
    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
         Country::where('id', '=', $idDecrypted)->delete();
         return true;
    }

    public function findById(string $id){
        $idDecryted = Crypt::decrypt($id);
        $findCountry = Country::select('countryName')->where('id', '=', $idDecryted)->first();
        if($findCountry){
            return $findCountry;
        }else{
            return false;
        }
    }

    public function getCountryByStateId($stateId){
    $idDecrypted = Crypt::decrypt($stateId);
    $state = State::select('countryId')->where('id', '=', $idDecrypted)->first();
    if($state){
        $country = Country::select('id', 'countryName')->where('id', '=', $state->countryId)->first();
        if($country){
            $country->id = Crypt::encrypt($country->id);
            return $country;
        }
    }
    return null;
}
}



?>