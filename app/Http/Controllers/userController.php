<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function __construct(protected UserService $userServices){}
    public function store(Request $req){
        try{
            $error = 0;
            $msg = "save";
            $create = $this->userServices->store($req->input());
            if($create){
                $res = [
                'error' => $error,
                'msg' => $msg
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
            return response()->json(['error' => 500, 'msg' => 'Error del servidor']);

        }
    }

    public function findAll(){
        try{
            $results = $this->userServices->findAll();
            $res = [
                'error' => 0,
                'msg' => 'resultados encontrados',
                'results' => $results
            ];
            return response()->json($res,200);
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => 'Error del servidor']);

        }
    }
}
