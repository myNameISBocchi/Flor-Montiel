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
}
