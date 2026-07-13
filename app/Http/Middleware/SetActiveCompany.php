<?php

namespace App\Http\Middleware;

use App\Core\Support\CompanyContext;
use App\Domains\Usuario\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        /** @var User $user */
        $user = auth()->user();

        if ($request->has('switch_company')) {
            $companyId = (int) $request->get('switch_company');
            abort_unless($user->companies()->where('companies.id', $companyId)->exists(), 403);
            app(CompanyContext::class)->set($companyId);

            return redirect($request->url());
        }

        $context = app(CompanyContext::class);

        if (! $context->id()) {
            $membership = $user->companies()->first();
            $context->set($membership?->pivot->company_id ?? $membership?->id);
        }

        return $next($request);
    }
}
