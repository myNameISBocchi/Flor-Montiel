<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use App\Services\StateService;
use Illuminate\Http\Request;

class stateController extends Controller
{
    public function __construct(protected StateService $stateService){}
    public function store(Request $req){
        try {
            $error = 0;
            $create = $this->stateService->store($req->input());
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
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function findAll(){
        try {
            
        $error = 0;
        $findAll = $this->stateService->findAll();
        $res = [
            'error' => $error,
            'msg' => Message::findAll(),
            'results' => $findAll
        ];
        return response()->json($res,200);
        } catch (\Exception $th) {
            //throw $th;
            dd($th);
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function update(string $id, Request $req){
        try {
            //code...
            $update = $this->stateService->update($id,$req->input());
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
        } catch (\Throwable $th) {
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
            //throw $th;
        }
    }

    public function delete(string $id){
        try {
            $delete = $this->stateService->delete($id);
                if($delete){
                    $res = [
                'error' => 0,
                'msg' => Message::delete()
            ]; 
            return response()->json($res,200);
                } 
        } catch (\Exception $th) {
            dd($th);
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
            //throw $th;
        }

    }
}
