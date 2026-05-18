<?php
namespace App\Services;
use App\Models\RolePrivilege;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class RolePrivilegeService{
    
    public function findPrivilegeByRoleId(string $roleId){
        $roleIdDecrypted = Crypt::decrypt($roleId);
        
        $results = RolePrivilege::select(
            'roles_privileges.id as rolePrivilegeId',
            'roles.id as roleId',
            'privileges.id as privilegeId',
            'privileges.privilegeName',
            'roles.roleName'
        )->join('roles', 'roles.id', '=', 'roles_privileges.roleId'
        )->join('privileges', 'privileges.id', '=', 'roles_privileges.privilegeId'
        )->where('roleId', '=', $roleIdDecrypted
        )->get()->map(function($rolePrivilegeTemp){
            $roleId = Crypt::encrypt($rolePrivilegeTemp->roleId);
            $privilegeId = Crypt::encrypt($rolePrivilegeTemp->privilegeId);
            $rolePrivilegeId = Crypt::encrypt($rolePrivilegeTemp->rolePrivilegeId);
            unset($rolePrivilegeTemp->roleId);
            unset($rolePrivilegeTemp->privilegeId);
            unset($rolePrivilegeTemp->rolePrivilegeId);
            $rolePrivilegeTemp->roleId = $roleId;
            $rolePrivilegeTemp->privilegeId = $privilegeId;
            $rolePrivilegeTemp->rolePrivilegeId = $rolePrivilegeId;
            return $rolePrivilegeTemp;
        })->toArray();
        
        return $results;
    }
    
    public function store(array $rolePrivilege)
    {
        $decryptedRoleId = Crypt::decrypt($rolePrivilege['roleId']);
        $arrPrivilegesId = json_decode($rolePrivilege['privilegeId']);
        
        RolePrivilege::where('roleId', $decryptedRoleId)->delete();
        
        $insert = [];
        for($i = 0; $i < count($arrPrivilegesId); $i++){
            $decryptedPrivilegesId = Crypt::decrypt($arrPrivilegesId[$i]);
            $insert[] = [
                'roleId' => $decryptedRoleId,
                'privilegeId' => $decryptedPrivilegesId
            ];
        }
        
        RolePrivilege::insert($insert);
        return true;
    }
}