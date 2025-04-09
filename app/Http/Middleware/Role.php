<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
     /**
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // si no está identificado no le dejamos acceder
        if (!auth()->check())
            return redirect('login');

        // obtenemos el usuario identificado
        $user = auth()->user();

        // si el usuario es Root le damos acceso a todo
        if ($user->isHostess())
            return $next($request);

        // si el usuario es Root le damos acceso a todo
        if ($user->isStylist())
            return $next($request);

        // si no es root y uno de los roles en las rutas es root no le dejamos acceder
        if (in_array("root", $roles))
            return redirect('dashboard');

        // si el usuario es admin le damos acceso, ya no es necesario permisos de root
        if($user->isAdmin())
            return $next($request);

        // en otro caso si el usuario tiene permisos con su rol puede acceder
        foreach($roles as $role) {
            if($user->role->name === $role)
                return $next($request);
        }

        // si no se cumple ninguna de las condiciones anteriores es que no tiene permisos
        return redirect('login');
    }
}
