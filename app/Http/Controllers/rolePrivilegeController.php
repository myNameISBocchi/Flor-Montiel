<?php

namespace App\Http\Controllers;

use App\Services\RolePrivilegeService;
use Illuminate\Http\Request;

class rolePrivilegeController extends Controller
{
    public function __construct(protected RolePrivilegeService $rolePrivileges){}
    public function findPrivilegeByRoleId(string $roleId){
    try{
        $results = $this->rolePrivileges->findPrivilegeByRoleId($roleId);
        
        return response()->json($results);
        
    } catch(\Exception $e){
        return response()->json(['error' => 500, 'msg' => $e->getMessage()], 500);
    }
}
    public function store(Request $req){
        try{
            $error = 0;
            $msg = 'save';
            $create = $this->rolePrivileges->store($req->input());
            if($create){
                $res = [
                    'error' => $error,
                    'msg' => $msg,
                ];
                return response()->json($res,200);
            }
        }catch(\Exception $e){
            
            return response()->json(['error' => 500, 'msg' => 'Error del servidor']);

        }

    }
}
