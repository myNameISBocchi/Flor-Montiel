<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use App\Services\CitieService;
use Illuminate\Http\Request;

class citieController extends Controller
{
    public function __construct(protected CitieService $citieService){}
    public function store(Request $req){
        try {
            $error = 0;
            $create = $this->citieService->store($req->input());
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
        } catch (\Exception $th) {
            dd($th);
            //throw $th;
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function findAll(){
        try {
            $error = 0;
            $findAll = $this->citieService->findAll();
            $res = [
                'error' => $error,
                'msg' => Message::findAll(),
                'results' => $findAll
            ];
            return response()->json($res,200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
            //throw $th;
        }
    }

    public function update(string $id, Request $req){
        try{
            $error = 0;
            $update = $this->citieService->update($id, $req->input());
            if($update){
                $res = [
                    'error' => $error,
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
            $error = 0;
            $delete = $this->citieService->delete($id);
            if($delete){
                $res = [
                    'error' => $error,
                    'msg' => Message::delete()
                ];
                return response()->json($res,200);

            }

        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);

        }

    }


}
