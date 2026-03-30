<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService )
    {
    }
    public function loggin(Request $req){
        try{
            $error = 0;
            $msg = 'Inicio de session exitoso';
            $loggin = $this->authService->loggin($req->input());
            if(!$loggin){
                $res = [
                    'error' => 1,
                    'msg' => 'Datos incorrectos'
                ];
                return response()->json($res,500);
            }else{
                $res = [
                    'error' => $error,
                    'msg' => $msg,
                    'results' => $loggin
                ];
                return response()->json($res,200);
            }

        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }
}
