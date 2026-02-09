<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
class UserService{
    public function store(array $user){
        $duplicate = User::select('id')->where([
            ['identification',$user['identification']],
            ['phone',$user['phone']],
            ['email',$user['email']],
        ])->first();
        if($duplicate){
            return false;
        }else{
            $user['comunitieId'] = Crypt::decrypt($user['comunitieId']);
            $user['councilId'] = Crypt::decrypt($user['councilId']);
            $user['committeeId'] = Crypt::decrypt($user['committeeId']);
            $user['roleId'] = Crypt::decrypt($user['roleId']);
            return User::create($user);
        }
    }

    public function findAll(){
        $result = User::select('users.id as userId',
        'firstName',
        'lastName',
        'email',
        'identification',
        'phone',
        'comunitieId as comunityId',
        'councilId as consejoId',
        'committeeId as comiteId',
        'roleId',
        'roles.roleName',
        'comunities.comunityName',
        'councils.councilName',
        'committees.committeeName',
        'status'
        )->join('comunities', 'users.comunitieId', '=', 'comunities.id'
        )->join(
            'councils', 'users.councilId', '=', 'councils.id'
        )->join(
            'committees', 'users.committeeId','=', 'committees.id'
        )->join(
            'roles', 'users.roleId', '=', 'roles.id'
        )->get()->map(function($item){
            $userIdEncrypt = Crypt::encrypt($item->userId);
            $roleIdEncrypt = Crypt::encrypt($item->roleId);
            $comunityIdEncrypt = Crypt::encrypt($item->comunityId);
            $consejoIdEncrpt = Crypt::encrypt($item->consejoId);
            $comiteIdEncrypt = Crypt::encrypt($item->comiteId);
            unset($item->roleId);
            unset($item->userId);
            unset($item->comunityId);
            unset($item->consejoId);
            unset($item->comiteId);
            $item->roleId = $roleIdEncrypt;
            $item->userId = $userIdEncrypt;
            $item->comunityId = $comunityIdEncrypt;
            $item->councilId = $consejoIdEncrpt;
            $item->committeeId = $comiteIdEncrypt;
           
            return $item;
        })->toArray();
        return $result;

    }

    public function update(string $id, array $user){

    }

    public function delete(string $id){

    }

    public function findById(string $id){

    }
}

?>