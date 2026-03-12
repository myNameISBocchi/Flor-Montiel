<?php
namespace App\Services;
use App\Models\Comunity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class ComunityService{

    public function store(array $comunity) {
        $findCom = Comunity::select('id')->where([
            ['comunityName', '=', $comunity['comunityName']],
            ['googleMaps', '=', $comunity['googleMaps']]
        ])->first();
        if($findCom){
            return false;
        }else{
            return Comunity::create($comunity);
        }
    }

    public function findAll(){
        $findAll = Comunity::select('id','comunityName', 'googleMaps', 'photoComunity'
        )->get()->map(function($comunityTemp){
            $idEncrypt = Crypt::encrypt($comunityTemp->id);
            $comunityTemp->comunityId = $idEncrypt;
            unset($comunityTemp->id);
            return $comunityTemp;
            
        });
            return $findAll;
    }

    public function update(string $id, array $comunity){
        $idDecrypted = Crypt::decrypt($id);
        $find = Comunity::select('id')->where([
            ['comunityName', '=', $comunity['comunityName']],
            ['googleMaps', '=', $comunity['googleMaps']],
            ['id', '!=', $idDecrypted]
        ])->first();
        if($find){
            return false;
        }else{
          return Comunity::where('id', '=', $idDecrypted)->update([
                'comunityName' => strtolower($comunity['comunityName']),
                'googleMaps' => strtolower($comunity['googleMaps']),
                
            ]);
        }

    }

    public function delete(string $id){
        $idDecrypted = Crypt::decrypt($id);
        Comunity::where('id', '=', $idDecrypted)->delete($idDecrypted);
        return true;


    }
    
     public function uploadPhoto(int $id, $file) 
{
    $comunity = Comunity::find($id);
    
    if (!$comunity) {
        return false;
    }
    if (!empty($comunity->photoComunity)) {
        $oldPath = str_replace('storage/', '', $comunity->photoComunity);
        
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
    $extension = $file->getClientOriginalExtension();
    $fileName = 'comunity_' . $id . '_' . time() . '.' . $extension;

    $path = $file->storeAs('comunities', $fileName, 'public');
    $comunity->photoComunity = 'storage/' . $path;
    $comunity->save();
    
    return $comunity;
}
         

}
?>