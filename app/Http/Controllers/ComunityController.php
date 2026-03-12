<?php

namespace App\Http\Controllers;

use App\Models\Comunity;
use App\Services\ComunityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt; // Asegúrate de tener esta importación

class ComunityController extends Controller
{
    public function __construct(protected ComunityService $ComunityService){

    }

    public function store(Request $req){
        try{
            $store = $this->ComunityService->store($req->input());
        $error = 0;
        $msg = 'saved';
        if(!$store){
            $res = [
                'error' => 1,
                'msg' => 'duplicado'
            ];
            return response()->json($res, 500);
        }else{
            $res = [
                'error' => $error,
                'msg' => $msg
            ];
        }
        return response()->json($res,200);
        }catch(\Exception $error){
            dd($error);
            return response()->json(['error' => 1, 'msg' => 'servidor caido']);

        }

    }

    public function findAll(){
        try{
            $findAll = $this->ComunityService->findAll();
            if($findAll){
                $res = [
                    'error' => 0,
                    'msg' => 'resultados encontrados',
                    'result' => $findAll
                ];
                return response()->json($res,200);
            }else{
                $res = [
                    'error' => 1,
                    'msg' => 'resultados no encontrados',
                    'result' => ''
                ];
                return response()->json(500,'error');

            }

        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => 'error del servidor']);

        }
    }

    public function update(Request $req, string $id){
        try{
            $update = $this->ComunityService->update($id, $req->input());
            if(!$update){
                $res = [
                    'error' => 1,
                    'msg' => 'duplicado',
                    'results' => ''
                ];
                return response()->json($res,500);
            }else{
                $res = [
                    'error' => 0,
                    'msg' => 'update',
                    'results' => ''
                ];
                return response()->json($res,200);
            }
            
        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 500, 'msg' => 'fallo del servidor']);

        }

    }

    public function delete(string $id){
        try{
            $delete = $this->ComunityService->delete($id);
            if($delete){
                $res = [
                    'error' => 0,
                    'result' => 'deleted'
                ];
                return response()->json($res,200);
            }

        }catch(\Exception $e){
             return response()->json(['error' => 1, 'msg' => 'servidor caido']);

        }

    }

    public function uploadPhoto(Request $req, $id){
        try{

            $req->validate([
                'photoComunity' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            ]); 

            $idDecrypted = Crypt::decrypt($id);

            if($req->hasFile('photoComunity')){
                $file = $req->file('photoComunity');
                
                
                $upload = $this->ComunityService->uploadPhoto($idDecrypted, $file);
                
                if($upload){
                    $res = [
                        'error' => 0,
                        'msg' => 'imagen guarda'
                    ];
                    return response()->json($res,200);
                }
            }
            return response()->json(['error' => 1, 'msg' => 'no se pudo guardar']);

        }catch(\Exception $e){
            
            return response()->json(['error' => 500, 'msg' => $e->getMessage()], 500);
        }
    }
}