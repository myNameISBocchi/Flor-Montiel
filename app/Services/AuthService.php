<?php
namespace App\Services;
use App\Models\Person;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService{
    public function loggin(array $auth){
        $key = '7f8c9d2e1a5b6c3d4e0f9a8b7c6d5e4f3a2b1c0d9e8f7a6b5c4d3e2f1a0b9c8d';
        $time = time();
        $expiredToken = 60 * 60; 

        
        $findPerson = Person::where('email', $auth['email'])->first();

        
        if(!$findPerson || !Hash::check($auth['password'], $findPerson->password)){
            return []; 
        }

        
        $roles = DB::table('peoples_roles')
            ->join('roles', 'peoples_roles.roleId', '=', 'roles.id')
            ->where('peoples_roles.personId', $findPerson->id)
            ->pluck('roles.roleName')
            ->toArray();

        
        $roleIds = DB::table('roles')->whereIn('roleName', $roles)->pluck('id')->toArray();
        
        $privileges = DB::table('roles_privileges')
            ->join('privileges', 'roles_privileges.privilegeId', '=', 'privileges.id')
            ->whereIn('roles_privileges.roleId', $roleIds)
            ->where('privileges.status', 1)
            ->pluck('privileges.route')
            ->toArray();

    
        $dataToEncode = [
            'iat' => $time,
            'exp' => $time + $expiredToken,
            'user' => [
                'id' => $findPerson->id,
                'firstName' => $findPerson->firstName,
                'lastName' => $findPerson->lastName,
                'email' => $findPerson->email,
                'roles' => $roles,
                'privileges' => $privileges
            ]
        ];

        $token = JWT::encode($dataToEncode, $key, 'HS256');
        return [
            [
                'token' => $token,
                'expires_in' => $expiredToken,
                'personId' => Crypt::encrypt($findPerson->id),
                'user' => [
                    'id' => Crypt::encrypt($findPerson->id),
                    'firstName' => $findPerson->firstName,
                    'lastName' => $findPerson->lastName,
                    'roles' => $roles,
                    'privileges' => $privileges
                ]
            ]
        ];
    }
}