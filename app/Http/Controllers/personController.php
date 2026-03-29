<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use App\Services\PersonService;
use Exception;
use Illuminate\Http\Request;

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
            dd($e);
            return response()->json(['error' => 500, Message::errorServer()]);
        }
    }
    public function findAll(){
        try{
            $findAll = $this->personService->findAll();
            if($findAll){
                $res = [
                    'error'=> 0,
                    'results' => $findAll

                ];
                return response()->json($res,200);

            }

        }catch(\Exception $e){
            dd($e);
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
                $res = [
                    'error' => 0,
                    'msg' => Message::updated()
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
            dd($e);
                return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
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
            dd($e);
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }
}
