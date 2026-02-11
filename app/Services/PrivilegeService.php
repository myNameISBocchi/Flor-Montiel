<?php
namespace App\Services;
use App\Models\Privilege;
use App\Models\RolePrivilege;
use Illuminate\Support\Facades\Crypt;

class PrivilegeService{
    public function store(array $privileges){
        $duplicate = Privilege::select('id')->where('privilegeName',$privileges['privilegeName'])->first();
        if($duplicate){
            return false;
        }else{
            return Privilege::create($privileges);
        }
    }

    public function findAll(){
        $findAll = Privilege::select('id', 'privilegeName')->get(
        )->map(function($privilegeObject){
            $used = RolePrivilege::where('privilegeId', '=', $privilegeObject->id)->first();
            if($used){
                $privilegeObject->blocked = 1;
            }else{
                $privilegeObject->blocked = 0;
            }
            $idDecrypted = Crypt::encrypt($privilegeObject->id);
            $privilegeObject->privilegeId = $idDecrypted;
            unset($privilegeObject->id);
            return $privilegeObject;
        });
        return $findAll;
    }
    
    public function update(string $id, array $privileges){
        $idDecrypted = Crypt::decrypt($id);
        $duplicate = Privilege::where([
            ['id',$idDecrypted],
            ['privilegeName',$privileges['privilegeName']]
        ])->first();
        if($duplicate){
            return false;
        }else{
            return Privilege::where('id',$idDecrypted)->update($privileges);
        }
    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        Privilege::where('id',$idDecrypted)->delete();
        return true;

    }
    
}

?>