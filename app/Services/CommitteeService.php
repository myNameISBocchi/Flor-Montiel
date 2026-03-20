<?php
namespace App\Services;
use App\Models\Committee;
use COM;
use Illuminate\Support\Facades\Crypt;

class CommitteeService{
    public function store(array $committee){
        $duplicate = Committee::select('id')->where(
            'committeeName', '=', $committee['committeeName'],
            
        )->first();
        if($duplicate){
            return false;
        }else{
            return Committee::create($committee);
        }
    }

    public function findAll(){
        $findAll = Committee::select('id', 'committeeName'
        )->get()->map(function($committeeTemp){
        $idEncrypt = Crypt::encrypt($committeeTemp->id);
        $committeeTemp->committeeId = $idEncrypt;
        unset($committeeTemp->id);
        return $committeeTemp;
        });
        return $findAll;
    }

    public function update(string $id, array $committee){
        $idDecrypted = Crypt::decrypt($id);
        $duplicado = Committee::where([
            ['id', '!=', $idDecrypted],
            ['committeeName', '=', $committee['committeeName']],
        ])->first();
        if($duplicado){
            return false;
        }else{
            return Committee::where('id', '=', $idDecrypted)->update($committee);
        }

    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
         Committee::where('id', '=', $idDecrypted)->delete();
         return true;
    }
}






?>