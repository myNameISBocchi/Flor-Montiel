<?php
namespace App\Services;
use App\Models\Person;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
class AuthService{
    public function loggin(array $auth){
        $msg = 'Welcome';
        $key = '7f8c9d2e1a5b6c3d4e0f9a8b7c6d5e4f3a2b1c0d9e8f7a6b5c4d3e2f1a0b9c8d';
        $error = 0;
        $time = time();
        $timeSession = 60*60;
        $expiredToken = $timeSession + 60;
        $findPerson = Person::select('id','firstName', 'lastName')->where([
            ['email',$auth['email']],
            ['password',$auth['password']]
        ])->first();

        $payload = [];
        if($findPerson){
            $roles = DB::table('peoples_roles')->join(
                'roles', 'peoples_roles.roleId', '=', 'roles.id'
            )->where('personId', '=', $findPerson->id
            )->pluck('roles.roleName')->toArray();

            $dataToEncode = [
                'iat' => $time,
                'exp' => $time + $expiredToken, 
                'user' => [
                    'id' => $findPerson->id,
                    'firstName' => $findPerson->firstName,
                    'lastName' => $findPerson->lastName,
                    'roles' => $roles
                ]
            ];

            $token = JWT::encode($dataToEncode, $key, 'HS256');
            $payload[] = [
                'iat' => $time,
                'expired' => $expiredToken,
                'token' => $token,
                'msg' => $msg,
                'error' => $error,
                'user' => [
                    'firstName' => $findPerson['firstName'],
                    'lastName' => $findPerson['lastName'],
                    'roles' => $roles
                ]
            ];
        }
        return $payload;
    }

}