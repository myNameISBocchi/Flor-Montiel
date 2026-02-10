<?php

namespace App\Http\Controllers;

use App\Services\CountryService;
use Illuminate\Http\Request;
use App\Helpers;
use App\Helpers\Message;

class countryController extends Controller
{
    public function __construct(protected CountryService $countryService){}
    public function store(Request $req){
        try{
        $store = $this->countryService->store($req->input());
        if($store){
            $res = [
                'error' => 0,
                'msg' => Message::stored()
            ];
            return response()->json($res,200);
        }else{
            $res = [
                'error' => 500,
                'msg' => Message::duplicate()
            ];
            return response()->json($res,500);
        }
        }catch(\Exception $e){
            dd($e);
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);

        }
    }

    public function findAll(){
        try{
        $error = 0;
        $findAll = $this->countryService->findAll();
        $res = [
        'error' => $error,
        'msg' => Message::findAll(),
        'results' => $findAll
        ];
        return response()->json($res,200);
        }catch(\Exception $e){
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function update(string $id, Request $req){
        try{
            $error = 0;
            $update = $this->countryService->update($id,$req->input());
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
        try {
            $error = 0;
            $delete = $this->countryService->delete($id);
            if($delete){
                $res = [
                'error' => $error,
                'msg' => Message::delete()
                ];
                return response()->json($res,200);
            }
        } catch (\Exception $th) {
            //throw $th;
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }

    public function findById(string $id){
        try {
            $error = 0;
            $findCountry = $this->countryService->findById($id);
            if($findCountry){
                $res = [
                'error' => $error,
                'msg' => Message::findById(),
                'results' => $findCountry
                ];
                return response()->json($res,200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 500, 'msg' => Message::errorServer()]);
        }
    }
}
