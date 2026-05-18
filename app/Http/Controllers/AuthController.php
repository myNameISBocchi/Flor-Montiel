<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }
    
    public function loggin(Request $req){
        try{
            $loggin = $this->authService->loggin($req->input());
            
            
            if(!$loggin || empty($loggin)){
                return response()->json([
                    'error' => 1,
                    'msg' => 'Datos incorrectos'
                ], 401);  
            }
            
            return response()->json([
                'error' => 0,
                'msg' => 'Inicio de sesión exitoso',
                'results' => $loggin
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'error' => 500, 
                'msg' => Message::errorServer(),
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}