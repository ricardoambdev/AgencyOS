<?php

namespace App\Core\Support;

use App\Domains\Company\Models\Company;
use Illuminate\Contracts\Auth\Authenticatable;

final class CompanyContext
{
    protected static ?int $companyId = null;

    public function set(?int $companyId): void
    {
        static::$companyId = $companyId;

        if ($companyId && session()->isStarted()) {
            session()->put('active_company_id', $companyId);
        }
    }

    public function id(): ?int
    {
        if (static::$companyId !== null) {
            return static::$companyId;
        }

        if (session()->isStarted() && session()->has('active_company_id')) {
            return (int) session()->get('active_company_id');
        }

        return null;
    }

    public function model(): ?Company
    {
        $id = $this->id();

        return $id ? Company::withoutGlobalScope('company')->find($id) : null;
    }

    public function for(Authenticatable $user): void
    {
        $membership = $user->companies()->first();

        if ($membership) {
            $this->set($membership->pivot->company_id ?? $membership->id);
        }
    }
}
