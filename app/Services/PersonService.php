<?php
namespace App\Services;
use App\Models\Person;
use App\Models\PersonCommittees;
use App\Models\PersonComunity;
use App\Models\PersonCouncil;
use App\Models\PersonRole;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonService{
    public function store(array $person){
        $arrRoles = json_decode($person['roleId'] ?? '[]');
        $idComunity = $person['comunityId'];
        $idCouncil = $person['councilId'];
        $idCommitte = $person['committeeId'];
         
        $duplicate = Person::where('identification', $person['identification'])
            ->orWhere('email', $person['email'])
            ->orWhere('phone', $person['phone'])
            ->first();
        if($duplicate){
            return false;
        } else {
            $countryDecrypted = Crypt::decrypt($person['cityId']);
            $person['cityId'] = $countryDecrypted;
            $newPerson = Person::create($person);
            
            PersonRole::where('personId', $newPerson->id)->delete();
            $rolesInsert = [];
            $voceroRol = DB::table('roles')->where('roleName', 'VOCERO')->first();
            if($voceroRol){
                $rolesInsert[] = [
                    'personId' => $newPerson->id,
                    'roleId' => $voceroRol->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            for($i = 0; $i < count($arrRoles); $i++){
                $roleIdDecrypted = Crypt::decrypt($arrRoles[$i]);
                if ($voceroRol && $roleIdDecrypted == $voceroRol->id) continue;
                $rolesInsert[] = [
                    'personId' => $newPerson->id,
                    'roleId' => $roleIdDecrypted,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if(!empty($rolesInsert)){
                PersonRole::insert($rolesInsert);
            }
            if(!empty($idComunity)){
                PersonComunity::create([
                    'personId' => $newPerson->id,
                    'comunityId' => Crypt::decrypt($idComunity)
                ]);
            }
            if(!empty($idCouncil)){
                PersonCouncil::create([
                    'personId' => $newPerson->id,
                    'councilId' => Crypt::decrypt($idCouncil)
                ]);
            }
            if(!empty($idCommitte)){
                PersonCommittees::create([
                    'personId' => $newPerson->id,
                    'committeeId' => Crypt::decrypt($idCommitte)
                ]);
            }
            
            return true;
        }
    }
    
    public function findAll(){
        $all = Person::select(
            'peoples.id as personId',
            'firstName',
            'lastName',
            'identification',
            'phone',
            'status',
            'date',
            'email',
            'cityName as city',
            'peoples.cityId as cityId',
            'countries.countryName as country',
            'states.stateName as state',
            'photoPerson',
            'comunities.comunityName',
            'councils.councilName',
            'committees.committeeName',
            'parent_committees.committeeName as parentName'
        )
        ->join('cities', 'peoples.cityId', '=', 'cities.id')
        ->join('states', 'cities.stateId', '=', 'states.id')
        ->join('countries', 'states.countryId', '=', 'countries.id')
        ->leftJoin('peoples_comunities', 'peoples.id', '=', 'peoples_comunities.personId')
        ->leftJoin('comunities', 'peoples_comunities.comunityId', '=', 'comunities.id')
        ->leftJoin('peoples_councils', 'peoples.id', '=', 'peoples_councils.personId')
        ->leftJoin('councils', 'peoples_councils.councilId', '=', 'councils.id')
        ->leftJoin('peoples_committees', 'peoples.id', '=', 'peoples_committees.personId')
        ->leftJoin('committees', 'peoples_committees.committeeId', '=', 'committees.id')
        ->leftJoin('committees as parent_committees', 'committees.parentId', '=', 'parent_committees.id')
        ->get()->map(function($item) {
            $roles = DB::table('peoples_roles')
                ->join('roles', 'peoples_roles.roleId', '=', 'roles.id')
                ->where('personId', '=', $item->personId)
                ->pluck('roleName')->toArray();
            
            $item->roleName = implode(', ', $roles);

            if (!empty($item->parentName)) {
                $item->committeeName = $item->parentName . ' - ' . $item->committeeName;
            }

            $blockedResult = PersonRole::select('id')->where('personId', '=', $item->personId)->first();
            $item->blocked = $blockedResult ? 1 : 0;

            $item->locality = [
                'city' => $item->city,
                'country' => $item->country,
                'state' => $item->state
            ];

            $personIdEncrypt = Crypt::encrypt($item->personId);
            $cityIdEncrypt = Crypt::encrypt($item->cityId);

            unset($item->city, $item->country, $item->state, $item->personId, $item->cityId, $item->parentName);

            $item->personId = $personIdEncrypt;
            $item->cityId = $cityIdEncrypt;

            return $item;
        });

        return $all;
    }

    public function findById(string $id){
        $idDecrypted = Crypt::decrypt($id);
        $findPerson = Person::select(
            'firstName', 'lastName', 'identification', 'phone', 
            'status', 'date', 'email', 'cityName as city',
            'countries.countryName as country', 'states.stateName as state',
            'photoPerson', 'comunities.comunityName', 
            'councils.councilName', 'committees.committeeName'
        )
        ->join('cities', 'peoples.cityId', '=', 'cities.id')
        ->join('states', 'cities.stateId', '=', 'states.id')
        ->join('countries', 'states.countryId', '=', 'countries.id')
        ->leftJoin('peoples_comunities', 'peoples.id', '=', 'peoples_comunities.personId')
        ->leftJoin('comunities', 'peoples_comunities.comunityId', '=', 'comunities.id')
        ->leftJoin('peoples_councils', 'peoples.id', '=', 'peoples_councils.personId')
        ->leftJoin('councils', 'peoples_councils.councilId', '=', 'councils.id')
        ->leftJoin('peoples_committees', 'peoples.id', '=', 'peoples_committees.personId')
        ->leftJoin('committees', 'peoples_committees.committeeId', '=', 'committees.id')
        ->where('peoples.id', '=', $idDecrypted)->first();

        if(!$findPerson) return false;

        $findPerson->roles = DB::table('peoples_roles')
            ->join('roles', 'peoples_roles.roleId', 'roles.id')
            ->where('personId', $idDecrypted)
            ->pluck('roleName')->toArray();

        return $findPerson;
    }
    
    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        Person::where('id', '=', $idDecrypted)->delete();
        return true;
    }

    public function uploadPhoto(int $id, $file){
        $person = Person::find($id);
        if(!empty($person->photoPerson)){
            $oldPath = str_replace('storage/', '', $person->photoPerson);
            if(Storage::disk('public')->exists($oldPath)){
                Storage::disk('public')->delete($oldPath);
            }   
        }
        $extension = $file->getClientOriginalExtension();
        $fileName = 'person_'. $id. '_'. time(). '.'. $extension;
        $path = $file->storeAs('persons', $fileName, 'public');
        $person->photoPerson = 'storage/'.$path;
        $person->save(); 
        return $person;
    }

    public function searchPerson(array $filters = []){
        $query = Person::query()
            ->select(
                'peoples.id as personId',
                'peoples.firstName',
                'peoples.lastName',
                'peoples.identification',
                'peoples.phone',
                'peoples.status',
                'peoples.date',
                'peoples.email',
                'peoples.cityId as cityId',
                'peoples.photoPerson',
                'cities.cityName as city',
                'states.stateName as state',
                'countries.countryName as country',
                'comunities.comunityName',
                'councils.councilName',
                'committees.committeeName'
            )
            ->join('cities', 'peoples.cityId', '=', 'cities.id')
            ->join('states', 'cities.stateId', '=', 'states.id')
            ->join('countries', 'states.countryId', '=', 'countries.id')
            ->leftJoin('peoples_comunities', 'peoples.id', '=', 'peoples_comunities.personId')
            ->leftJoin('comunities', 'peoples_comunities.comunityId', '=', 'comunities.id')
            ->leftJoin('peoples_councils', 'peoples.id', '=', 'peoples_councils.personId')
            ->leftJoin('councils', 'peoples_councils.councilId', '=', 'councils.id')
            ->leftJoin('peoples_committees', 'peoples.id', '=', 'peoples_committees.personId')
            ->leftJoin('committees', 'peoples_committees.committeeId', '=', 'committees.id')
            ->distinct();

        if (!empty($filters['firstName'])) {
            $term = $filters['firstName'];
            $query->where(function($q) use ($term) {
                $q->where('peoples.firstName', 'LIKE', '%' . $term . '%')
                  ->orWhere('peoples.lastName', 'LIKE', '%' . $term . '%')
                  ->orWhere('peoples.identification', 'LIKE', '%' . $term . '%')
                  ->orWhere('comunities.comunityName', 'LIKE', '%' . $term . '%')
                  ->orWhere('councils.councilName', 'LIKE', '%' . $term . '%')
                  ->orWhere('committees.committeeName', 'LIKE', '%' . $term . '%');
            });
        }

        try {
            if (!empty($filters['comunityId'])) {
                $query->where('comunities.id', Crypt::decrypt($filters['comunityId']));
            }
            if (!empty($filters['councilId'])) {
                $query->where('councils.id', Crypt::decrypt($filters['councilId']));
            }
            if (!empty($filters['committeeId'])) {
                $query->where('committees.id', Crypt::decrypt($filters['committeeId']));
            }
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        }

        return $query->get()->map(function($object) {
            $roles = DB::table('peoples_roles')
                ->join('roles', 'peoples_roles.roleId', '=', 'roles.id')
                ->where('personId', '=', $object->personId)
                ->pluck('roleName')->toArray();
            
            $object->roleName = implode(', ', $roles);
            $object->blocked = DB::table('peoples_roles')->where('personId', $object->personId)->exists();
            
            $object->locality = [
                'city' => $object->city,
                'state' => $object->state,
                'country' => $object->country
            ];

            $personIdEncrypt = Crypt::encrypt($object->personId);
            $cityIdEncrypt = Crypt::encrypt($object->cityId);

            unset($object->city, $object->state, $object->country, $object->cityId);

            $object->personId = $personIdEncrypt;
            $object->cityId = $cityIdEncrypt;

            return $object;
        });
    }
    
    public function update(string $id, array $person) {
        $idDecrypted = Crypt::decrypt($id);
        
        $duplicate = Person::where('id', '!=', $idDecrypted)
            ->where(function($query) use ($person) {
                $query->where('identification', $person['identification'])
                      ->orWhere('email', $person['email'])
                      ->orWhere('phone', $person['phone']);
            })
            ->exists();

        if (!$duplicate) {
            $model = Person::find($idDecrypted);
            if ($model) {
                if (isset($person['cityId'])) {
                    $person['cityId'] = Crypt::decrypt($person['cityId']);
                }
                if (empty($person['password'])) {
                    unset($person['password']);
                }
                $model->fill($person);
                return $model->save();
            }
        }
        return false;
    }

    public function updateRoles(string $id, array $roleId){
        $idDecrypted = Crypt::decrypt($id);
        return DB::transaction(function() use ($idDecrypted, $roleId){
            DB::table('peoples_roles')->where('personId', $idDecrypted)->delete();
            $rolesInsert = [];
            foreach($roleId as $key => $rolesId){
                $rolesInsert[] = [
                    'personId' => $idDecrypted,
                    'roleId' => Crypt::decrypt($rolesId),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if(!empty($rolesInsert)){
                return DB::table('peoples_roles')->insert($rolesInsert);
            }
            return true;
        });
    }

    public function updateOwn(int $userId, array $data)
{
    $person = Person::find($userId);
    
    if (!$person) {
        return false;
    }
    
    if (isset($data['firstName'])) {
        $person->firstName = $data['firstName'];
    }
    if (isset($data['lastName'])) {
        $person->lastName = $data['lastName'];
    }
    if (isset($data['email'])) {
        $person->email = $data['email'];
    }
    if (isset($data['phone'])) {
        $person->phone = $data['phone'];
    }
    if (!empty($data['password'])) {
        $person->password = $data['password'];
    }
    
    $saved = $person->save();
    
    return $saved;
}

    public function uploadOwnPhoto(int $userId, $file)
{
    $person = Person::find($userId);
    
    if (!$person) {
        return false;
    }
    
    if (!empty($person->photoPerson)) {
        $oldPath = str_replace('storage/', '', $person->photoPerson);
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
    
    $extension = $file->getClientOriginalExtension();
    $fileName = 'person_' . $userId . '_' . time() . '.' . $extension;
    $path = $file->storeAs('persons', $fileName, 'public');
    $person->photoPerson = 'storage/' . $path;
    $person->save();
    
    return $person;
}

public function getVocerosByConsejo(array $filters = [])
{
    $personas = $this->searchPerson($filters);
    return $personas->groupBy('committeeName');
}
}