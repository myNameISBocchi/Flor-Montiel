<?php
namespace App\Services;
use App\Models\Role;
use App\Models\RolePrivilege;
use Illuminate\Support\Facades\Crypt;

class RoleService{
    public function store(array $role){
        $duplicado = Role::select('id')->where('roleName', '=', $role['roleName'])->first();
        if($duplicado){
            return false;
        }else{
            return Role::create($role);
        }
    }

    public function findAll(){
        $findAll = Role::select('id', 'roleName')->get()->map(function($roleObject){
            $idEncrypt = Crypt::encrypt($roleObject->id);
            $used = RolePrivilege::where('roleId', '=', $roleObject->id)->first();
            if($used){
                $roleObject->blocked = 1;
            }else{
                $roleObject->blocked = 0;
            }
            $roleObject->roleId = $idEncrypt;
            unset($roleObject->id);
            return $roleObject;
        });
        return $findAll;
    }

    public function update(string $id, array $role){
        $idDecrypted = Crypt::decrypt($id);
        $repet = Role::where([
            ['id', '!=', $idDecrypted],
            ['roleName', '=', $role['roleName']]
        ])->first();
        if($repet){
            return false;
        }else{
            return Role::where('id', '=',$idDecrypted)->update($role);
        }
    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        Role::where('id', '=', $idDecrypted)->delete();
        return true;
    }

}
?>