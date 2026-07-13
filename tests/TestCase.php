<?php

namespace Tests;

use App\Core\Support\CompanyContext;
use App\Domains\Company\Models\Company;
use App\Domains\Company\Models\Role;
use App\Domains\Usuario\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function makeCompany(string $name = 'Test Company'): Company
    {
        return Company::create([
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name).'-'.\Illuminate\Support\Str::random(6),
        ]);
    }

    protected function makeUser(array $attrs = []): User
    {
        return User::create(array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ], $attrs));
    }

    protected function ownerUser(array $attrs = []): User
    {
        $company = $this->makeCompany();
        $role = Role::create([
            'company_id' => $company->id,
            'name' => 'Owner',
            'slug' => 'owner',
            'capabilities' => ['*'],
        ]);
        $user = $this->makeUser($attrs);
        $company->users()->attach($user->id, [
            'ulid' => (string) \Illuminate\Support\Str::ulid(),
            'role_id' => $role->id,
            'status' => 'active',
            'accepted_at' => now(),
        ]);
        app(CompanyContext::class)->set($company->id);

        return $user;
    }

    protected function memberUser(array $attrs = []): User
    {
        $company = $this->makeCompany('Member Company');
        $role = Role::create([
            'company_id' => $company->id,
            'name' => 'Member',
            'slug' => 'member',
            'capabilities' => ['crm.view', 'projeto.view'],
        ]);
        $user = $this->makeUser($attrs);
        $company->users()->attach($user->id, [
            'ulid' => (string) \Illuminate\Support\Str::ulid(),
            'role_id' => $role->id,
            'status' => 'active',
            'accepted_at' => now(),
        ]);
        app(CompanyContext::class)->set($company->id);

        return $user;
    }
}
