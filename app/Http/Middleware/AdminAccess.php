<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Permite acesso ao painel administrativo apenas para perfis com
     * capacidade total ('*') ou papéis de gestão.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $role = method_exists($user, 'currentRole') ? $user->currentRole() : null;
        $allowed = $user->canCapability('*')
            || ($role && in_array($role->slug, ['owner', 'admin', 'gestor', 'manager'], true));

        abort_unless($allowed, 403, 'Acesso restrito à administração.');

        return $next($request);
    }
}
