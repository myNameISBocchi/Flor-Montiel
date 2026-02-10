<?php
namespace App\Services;
use App\Models\Country;
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
            $idEncrypted = Crypt::encrypt($item->id);
            $item->countryId = $idEncrypted;
            unset($item->id);
            return $item;
        });
        return $findAll;
    }

    public function update(string $id, array $country){
        $idDcrypted = Crypt::decrypt($id);
        $duplicate = Country::where([
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
}



?>