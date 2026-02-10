<?php
namespace App\Services;
use App\Models\State;
use Illuminate\Support\Facades\Crypt;
class StateService{
    public function store(array $state){
        $findState = State::select('id')->where('stateName', $state['stateName'])->first();
        if($findState){
            return false;
        }else{
            $idCountryDecrypted = Crypt::decrypt($state['countryId']);
            $state['countryId'] = $idCountryDecrypted;
            return State::create($state);
        }
    }

    public function findAll(){
        $results = State::select('stateName','countries.countryName','states.id as stateId', 'countries.id as countryId')->join('countries', 'states.countryId', '=', 'countries.id')->get()->map(function($item){
            $idEncrypt = Crypt::encrypt($item->stateId);
            $idCountry = Crypt::encrypt($item->countryId);
            unset($item->stateId);
            unset($item->countryId);
            $item->stateId = $idEncrypt;
            $item->countryId = $idCountry;
            return $item;
        });
        return $results;
    }

    public function update(string $id, array $state){
        $idDecryptd = Crypt::decrypt($id);
        $find = State::where([
            ['id', '!=', $idDecryptd],
            ['stateName', '=', $state['stateName']]
        ])->first();
        if($find){
            return false;
        }else{
            $state['countryId'] = Crypt::decrypt($state['countryId']);
            return State::where('id', '=', $idDecryptd)->update($state);
        }
    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        State::where('id', '=', $idDecrypted)->delete();
        return true;

    }
    
}


?>