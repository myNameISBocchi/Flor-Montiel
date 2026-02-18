<?php

namespace App\Services; 
use App\models\Council;
use Illuminate\Support\Facades\Crypt;

class CouncilService{
    public function store(array $council){
        $findCounilName = Council::select('id')->where('councilName', '=', $council['councilName'])->first();
            if($findCounilName){
                return false;
            }else{
                $council['comunityId'] = $council['comunityId'];
                return Council::create($council);
            }
    }
    public function findAll(){
        $councilAll = Council::select('councils.id',
        'councilName',
        'comunityName',
        'comunityId', 
        'councils.googleMaps', 
        'photoCouncil',
        'comunities.id as comunityId'
        )->join('comunities', 'councils.comunityId', '=', 'comunities.id')->get()->map(function($councilTemp){
            $idEncrypt = Crypt::encrypt($councilTemp->id);
            $comunityIdCrypt = Crypt::encrypt($councilTemp->comunityId);
            unset($councilTemp->comunityId);
            $councilTemp->comunityId = $comunityIdCrypt;
            $councilTemp->councilId = $idEncrypt;
            unset($councilTemp->id);
            return $councilTemp;
        });
        return $councilAll;
    }
    
    public function update(string $id, array $council){
         $idDecrypted = Crypt::decrypt($id);
        $find = Council::select('id')->where([
            ['id', '!=', $idDecrypted],
            ['councilName', '=', $council['councilName']]
            ])->first();
        if($find){
            return false;
        }else{
            return Council::where('id', '=' , $idDecrypted)->update($council);
        }

    }
    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        return Council::where('id', '=', $idDecrypted)->delete($idDecrypted);
    }

}




?>