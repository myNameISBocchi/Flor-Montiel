<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommitteeService;
use Exception;

class CommitteeController extends Controller
{
    public function __construct(protected CommitteeService $committeeService){}
    public function store(Request $request){
        try{
        $create = $this->committeeService->store($request->input());
        if($create){
            $res = [
            'error' => 0,
            'msg' => 'saved'
            ];
            return response()->json($res,200);
        }else{
            $res = [
            'error' => 1,
            'msg' => 'duplicado'
            ];
            return response()->json($res,500);
        }
        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 500, 'msg' => 'error del servidor']);
        }
    }
    public function findAll(){
        try{
        $error = 0;
        $findAll = $this->committeeService->findAll();
        if($findAll){
            $res = [
            'error' => $error,
            'results' => $findAll
            ];
            return response()->json($res,200);
        }
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => 'error del servidor']);

        }
    }

    public function update(string $id, Request $req){
        try{
        $update = $this->committeeService->update($id, $req->input());
        if($update){
            $res = [
            'error' => 0,
            'msg' => 'update'
            ];
            return response()->json($res,200);
        } else{
            $res = [
                'error' => 1,
                'msg' => 'duplicado'
            ];
            return response()->json($res,500);
        }
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => 'error del servidor']);

        }

    }
}
