<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CouncilService;
use Exception;

class CouncilController extends Controller
{
    public function __construct(protected CouncilService $councilService){}
    public function store(Request $req){
        try{
            $error = 0;
            $msg = 'save';
            $create = $this->councilService->store($req->input());
            if($create){
                $res = [
                'msg' => $msg,
                'error' => $error
                ];
            }else{
                $res = [
                    'error' => 1,
                    'msg' => 'duplicado'
                ];
            }
            return response()->json($res,200);
        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 1, 'msg' => 'error del servidor']);
        }

    }
    public function findAll(){
        try{
            $findAll = $this->councilService->findAll();
            if($findAll){
                $res = [
                    'error' => 0,
                'result' => $findAll
                ];
            }else{
                $res = [
                'result' => 'no hay registros'
                ];  
            }
            return response()->json($res, 200);

        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 500, 'msg' => 'error del servidor' ]);

        }
    }

    public function update(string $id, Request $req){
        try{

        $update = $this->councilService->update($id, $req->input());
        if($update){
            $res = [
                'error' => 0,
                'msg' => 'update data'
            ];
            return response()->json($res,200);
        }else{
            $res = [
                'error' => 1,
                'msg' => 'duplicated'
            ];
            return response()->json($res,500);
        }
        

        }catch(Exception $e){
            dd($e);
            return response()->json(['error' => 500, 'msg' => 'Error del servidor']);

        }

    }

    public function delete(string $id){
        try{
            $delete = $this->councilService->delete($id);
            if($delete){
                $res = [
                'error' => 0,
                'msg' => 'delete'
            ];
            return response()->json($res,200);
            }
            

        }catch(Exception $e){
            return response()->json(['error' => 500, 'msg' => 'error del servidor']);

        }

    }
}
