<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommitteeService;
use App\Helpers\Message;

class committeeController extends Controller
{
    public function __construct(protected CommitteeService $committeeService){}
    public function store(request $req){
        try{
            $error = 0;
            $create = $this->committeeService->store($req->input());
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
                return response()->json($res, 500);
            }
        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 500, Message::errorServer()]);
        }
    }

    public function findAll(){
        try{
            $findAll = $this->committeeService->findAll();
            $res = [
                'error' => 0,
                'result' => $findAll
            ];
            if(!empty($findAll)){
                return response()->json($res,200);
            }else{
                $res = [
                    'error' => 1,
                    'msg' => 'Sin Registros'
                ];
                return response()->json($res,500);
            }

        }catch(\Exception $e){

        }
    }
}
