<?php
namespace App\Services;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Crypt;
class UserService{
    public function store(array $user){
        $arrRoles = json_decode($user['roleId']);
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
            $user['countryId'] = Crypt::decrypt($user['countryId']);
            $user =  User::create($user);

            UserRole::where('userId',$user->id)->delete();
            $insert = [];
            for($i = 0; $i < count($arrRoles); $i++){
                $idRoleDecrypted = Crypt::decrypt($arrRoles[$i]);
                $insert[] =[
                'userId' => $user->id,
                'roleId' => $idRoleDecrypted
                ];
            }
            UserRole::insert($insert);
            return true;
        }
    }

    public function findAll(){
        $result = User::select('users.id as userId',
        'firstName',
        'lastName',
        'email',
        'identification',
        'phone',
        'countryId',
        'comunitieId as comunityId',
        'councilId as consejoId',
        'committeeId as comiteId',
        'comunities.comunityName',
        'councils.councilName',
        'committees.committeeName',
        'cities.cityName as city',
        'countries.countryName as country',
        'states.stateName as state',
        'status'
        )->join('comunities', 'users.comunitieId', '=', 'comunities.id'
        )->join(
            'councils', 'users.councilId', '=', 'councils.id'
        )->join(
            'committees', 'users.committeeId','=', 'committees.id'
        )->join(
            'countries', 'users.countryId', '=', 'countries.id'
        )->join(
            'states', 'cities.stateId', '=', 'states.id'
        )->join(
            'countries', 'cities.countryId', '=', 'countries.id'
        )->get()->map(function($item){
            $userIdEncrypt = Crypt::encrypt($item->userId);
            $roleIdEncrypt = Crypt::encrypt($item->roleId);
            $comunityIdEncrypt = Crypt::encrypt($item->comunityId);
            $consejoIdEncrpt = Crypt::encrypt($item->consejoId);
            $comiteIdEncrypt = Crypt::encrypt($item->comiteId);
            $item->locality = [
                'city' => $item->city,
                'country' => $item->country,
                'state' => $item->state
            ];
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