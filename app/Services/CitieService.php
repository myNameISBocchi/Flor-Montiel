<?php
namespace App\Services;
use App\Models\Citie;
use Illuminate\Support\Facades\Crypt;
class CitieService{
    public function store(array $citie){
        $find = Citie::select('id')->where('cityName', '=', $citie['cityName'])->first();
        if($find){
            return false;
        }else{
            $citie['stateId'] = Crypt::decrypt($citie['stateId']);
            return Citie::create($citie);
        }
    }

    public function findAll(){
        $results = Citie::select('cityName','states.stateName','cities.id as cityId', 'cities.stateId as stateId'
        )->join('states', 'cities.stateId', '=', 'states.id')->get()->map(function($item){
            $idCityEncrypt = Crypt::encrypt($item->cityId);
            $idStateEncrypt = Crypt::encrypt($item->stateId);
            unset($cityId);
            unset($stateId);
            $item->cityId = $idCityEncrypt;
            $item->stateId = $idStateEncrypt;
            return $item;
        });
        return $results;
    }

    public function update (string $id, array $citie){
        $idDecrypted = Crypt::decrypt($id);
        $findCitie = Citie::where([
            ['id', '!=', $idDecrypted],
            ['cityName', '=', $citie['cityName']]
        ])->first();
        if($findCitie){
            return false;
        }else{
            $citie['stateId'] = Crypt::decrypt($citie['stateId']);
            return Citie::where('id', '=', $idDecrypted)->update($citie);
        }
    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        Citie::where('id', '=', $idDecrypted)->delete();
        return true;
    }
}

?>