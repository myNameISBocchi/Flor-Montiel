<?php

namespace App\Http\Controllers;

use App\Helpers\Message;

use Illuminate\Support\Facades\Crypt;
use App\Services\PersonService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class personController extends Controller
{
    public function __construct(protected PersonService $personService){}
    
    public function store(Request $req){
        try{
            $error = 0;
            $create = $this->personService->store($req->input());
            if($create){
                $res = [
                    'error' => $error,
                    'msg' => Message::stored()
                ];
                return response()->json($res,200);
            }else{
                $res = [
                    'error' => 1,
                    'msg' => Message::duplicate()
                ];
                return response()->json($res,500);
            }
        }catch(\Exception $e){
            return response()->json(['error' => 500, Message::errorServer()]);
        }
    }
    
    public function findAll(Request $req){
        try{
            $perPage = $req->query('perPage',10);
            $findAll = $this->personService->findAll($perPage);
            if($findAll){
                $res = [
                    'error'=> 0,
                    'results' => $findAll
                ];
                return response()->json($res,200);
            }
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }
    
    public function findById(string $id){
        try{
            $error = 0;
            $findById = $this->personService->findById($id);
            if($findById){
                $res = [
                    'error' => $error,
                    'msg' => Message::findById(),
                    'results' => $findById
                ];
                return response()->json($res,200);
            }else{
                $res = [
                    'error' => 1,
                    'msg' => 'User not found'
                ];
                return response()->json($res,500);
            }
        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function update(string $id, Request $req){
        try{
            $update = $this->personService->update($id, $req->input());
            if($update){
                return response()->json([
                    'error' => 0,
                    'msg' => Message::updated()
                ], 200);
            } else {
                return response()->json([
                    'error' => 1,
                    'msg' => Message::duplicate()
                ], 400);
            }
        } catch(\Exception $e){
            return response()->json(['error' => 1, 'msg' => Message::errorServer()]);
        }
    }
    
    public function updateOwn(Request $req)
    {
        $userId = request()->attributes->get('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 1, 'msg' => 'No user id'], 401);
        }
        
        $data = $req->input();
        
        try {
            $person = Person::find($userId);
            if (!$person) {
                return response()->json(['error' => 1, 'msg' => 'User not found'], 404);
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
            
            $person->save();
            
            return response()->json(['error' => 0, 'msg' => 'Perfil actualizado', 'data' => $person]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 1, 'msg' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }

    public function uploadPhotoOwn(Request $req)
    {
        try {
            $req->validate([
                'photoPerson' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            ]);
            
            $userId = request()->attributes->get('user_id');
            
            if (!$userId) {
                return response()->json(['error' => 1, 'msg' => 'Usuario no autenticado'], 401);
            }
            
            $file = $req->file('photoPerson');
            
            $upload = $this->personService->uploadOwnPhoto((int)$userId, $file);
            
            if ($upload) {
                return response()->json(['error' => 0, 'msg' => 'Foto actualizada correctamente'], 200);
            }
            
            return response()->json(['error' => 1, 'msg' => 'Error al subir la foto'], 500);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 1, 'msg' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }
        
    public function delete(string $id){
        try{
            $delete = $this->personService->delete($id);
            if($delete){
                $res = [
                    'error' => 0,
                    'msg' => Message::delete()
                ];
                return response()->json($res,200);
            }else{
                $res = [
                    'error' => 1,
                    'msg' => Message::errorServer()
                ];
                return response()->json($res,500);
            }
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function uploadPhoto(Request $req, $id){
        try{
            $req->validate([
                'photoPerson' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            ]); 

            $idDecrypted = Crypt::decrypt($id);
            $file = $req->file('photoPerson');

            $upload = $this->personService->uploadPhoto($idDecrypted, $file);
            if($upload){
                $res = [
                    'error' => 0,
                    'msg' => 'imagen guardada'
                ];
                return response()->json($res,200);
            }
            return response()->json(['error' => 1, 'msg' => 'no se pudo guardar'], 500);
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function searchPerson(Request $req){
        try{
            $filters = $req->only([
                'comunityId', 
                'councilId', 
                'committeeId', 
                'identification', 
                'firstName', 
                'lastName'
            ]);

            $perPage = $req->query('perPage',10);
            $search = $this->personService->searchPerson($filters, $perPage);
            if($search){
                $res = [
                    'error' => 0,
                    'msg' => 'resultados encontrados',
                    'results' => $search
                ];
                return response()->json($res,200);
            }else{
                $res = [
                    'error' => 1,
                    'msg' => 'No se encontraron los registros',
                    'results' => $search
                ];
                return response()->json($res,200);
            }
        }catch(\Exception $e){
            return response()->json([
                'error' => 500, 
                'msg' => Message::errorServer(),
            ], 500);
        }
    }

    public function assignRoles(Request $req, $id){
        try{
            $update = $this->personService->updateRoles($id, $req->input('roles',[]));
            if($update){
                $res = [
                    'error' => 0,
                    'msg' => 'Assing'
                ];
                return response()->json($res, 200);
            }
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }
}