<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;

class AuthMiddleware
{
    protected $key = '7f8c9d2e1a5b6c3d4e0f9a8b7c6d5e4f3a2b1c0d9e8f7a6b5c4d3e2f1a0b9c8d';

    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => 1,
                'msg' => 'Token no proporcionado, por favor inicie sesión'
            ], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            
            $userId = $decoded->user->id ?? null;
            
            if (!$userId) {
                return response()->json(['error' => 1, 'msg' => 'Usuario no identificado'], 401);
            }
            
            $currentPath = $request->path();
            
            if (str_contains($currentPath, 'update-own') || str_contains($currentPath, 'upload-photo-own')) {
                $request->attributes->add([
                    'auth_user' => $decoded->user,
                    'user_roles' => [],
                    'user_id' => $userId,
                    'user_permissions' => []
                ]);
                return $next($request);
            }
            
            $userRoles = DB::table('peoples_roles')
                ->join('roles', 'peoples_roles.roleId', '=', 'roles.id')
                ->where('peoples_roles.personId', $userId)
                ->pluck('roles.roleName')
                ->toArray();

            if (empty($userRoles)) {
                return response()->json([
                    'error' => 1, 
                    'msg' => 'Usuario sin roles asignados'
                ], 403);
            }

            $roleIds = DB::table('roles')
                ->whereIn('roleName', $userRoles)
                ->pluck('id')
                ->toArray();

            $allowedRoutes = DB::table('roles_privileges')
                ->join('privileges', 'roles_privileges.privilegeId', '=', 'privileges.id')
                ->whereIn('roles_privileges.roleId', $roleIds)
                ->pluck('privileges.route')
                ->toArray();

            $currentRoute = $request->route()->getName();
            
            if (empty($currentRoute)) {
                $currentRoute = $request->method() . ':' . $currentPath;
            }

            $hasAccess = $this->checkRouteAccess($allowedRoutes, $currentRoute, $userRoles);

            if (!$hasAccess) {
                return response()->json([
                    'error' => 1,
                    'msg' => 'Acceso denegado. No tienes permiso para esta acción.',
                    'required_route' => $currentRoute,
                    'your_roles' => $userRoles,
                    'your_permissions' => $allowedRoutes
                ], 403);
            }

            $request->attributes->add([
                'auth_user' => $decoded->user,
                'user_roles' => $userRoles,
                'user_id' => $userId,
                'user_permissions' => $allowedRoutes
            ]);
            
            return $next($request);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 1,
                'msg' => 'Sesión inválida o expirada',
                'detalle' => $e->getMessage()
            ], 401);
        }
    }

    private function checkRouteAccess(array $allowedRoutes, string $currentRoute, array $userRoles): bool
    {
        if (in_array('ADMINISTRADOR', $userRoles)) {
            return true;
        }

        if (in_array($currentRoute, $allowedRoutes)) {
            return true;
        }
        
        foreach ($allowedRoutes as $route) {
            if (str_ends_with($route, '.*')) {
                $prefix = substr($route, 0, -2);
                if (str_starts_with($currentRoute, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }
}