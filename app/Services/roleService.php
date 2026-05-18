<?php
namespace App\Services;
use App\Models\Role;
use App\Models\RolePrivilege;
use App\Models\Privilege;
use App\Models\PersonRole;
use Illuminate\Support\Facades\Crypt;

class RoleService{
    
    private function getEssentialPrivileges()
    {
        $essentialRoutes = [
            'auth.login',
            'peoples.show',
        ];
        
        return Privilege::whereIn('route', $essentialRoutes)->pluck('id')->toArray();
    }
    
    public function store(array $role){
        $duplicado = Role::select('id')->where('roleName', '=', $role['roleName'])->first();
        if($duplicado){
            return false;
        } else {
            $newRole = Role::create($role);
            
            $essentialPrivileges = $this->getEssentialPrivileges();
            
            foreach ($essentialPrivileges as $privilegeId) {
                $exists = RolePrivilege::where('roleId', $newRole->id)
                    ->where('privilegeId', $privilegeId)
                    ->exists();
                    
                if (!$exists) {
                    RolePrivilege::create([
                        'roleId' => $newRole->id,
                        'privilegeId' => $privilegeId
                    ]);
                }
            }
            
            return $newRole;
        }
    }

    public function findAll(){
        $findAll = Role::select('id', 'roleName')->get()->map(function($roleObject){
            $idEncrypt = Crypt::encrypt($roleObject->id);
            $RolePrivilege = RolePrivilege::where('roleId', '=', $roleObject->id)->first();
            $personRole = PersonRole::where('roleId', '=', $roleObject->id)->first();
            if($personRole || $RolePrivilege){
                $roleObject->blocked = 1;
            } else {
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
        $repet = Role::select('id')->where([
            ['id', '!=', $idDecrypted],
            ['roleName', '=', $role['roleName']]
        ])->first();
        if($repet){
            return false;
        } else {
            return Role::where('id', '=', $idDecrypted)->update($role);
        }
    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        Role::where('id', '=', $idDecrypted)->delete();
        return true;
    }
}