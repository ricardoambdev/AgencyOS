<?php

namespace App\Domains\Config\Controllers;

use App\Core\Support\Capabilities;
use App\Domains\Company\Models\CompanyUser;
use App\Domains\Company\Models\Role;
use App\Domains\Usuario\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('company_id', app(\App\Core\Support\CompanyContext::class)->id())
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view('config.roles.index', compact('roles'));
    }

    public function create()
    {
        $capabilities = Capabilities::groups();
        $users = User::whereHas('companies', function ($q) {
            $q->where('company_id', app(\App\Core\Support\CompanyContext::class)->id());
        })->orderBy('name')->get();

        return view('config.roles.create', compact('capabilities', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:60|regex:/^[a-z0-9_]+$/',
            'capabilities' => 'nullable|array',
            'capabilities.*' => 'string',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $role = Role::create([
            'company_id' => app(\App\Core\Support\CompanyContext::class)->id(),
            'name' => $data['name'],
            'slug' => $data['slug'],
            'capabilities' => $data['capabilities'] ?? [],
        ]);

        $this->syncMembers($role, $data['users'] ?? []);

        return redirect()->route('config.roles.index')
            ->with('status', 'Função criada.');
    }

    public function edit(Role $role)
    {
        $capabilities = Capabilities::groups();
        $users = User::whereHas('companies', function ($q) {
            $q->where('company_id', app(\App\Core\Support\CompanyContext::class)->id());
        })->orderBy('name')->get();

        $assigned = CompanyUser::where('company_id', $role->company_id)
            ->where('role_id', $role->id)
            ->pluck('user_id')
            ->toArray();

        return view('config.roles.edit', compact('role', 'capabilities', 'users', 'assigned'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:60|regex:/^[a-z0-9_]+$/',
            'capabilities' => 'nullable|array',
            'capabilities.*' => 'string',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $role->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'capabilities' => $data['capabilities'] ?? [],
        ]);

        $this->syncMembers($role, $data['users'] ?? []);

        return redirect()->route('config.roles.index')
            ->with('status', 'Função atualizada.');
    }

    public function destroy(Role $role)
    {
        if ($role->slug === 'owner') {
            return redirect()->route('config.roles.index')
                ->with('error', 'A função Proprietário não pode ser removida.');
        }

        CompanyUser::where('role_id', $role->id)->update(['role_id' => null]);
        $role->delete();

        return redirect()->route('config.roles.index')
            ->with('status', 'Função removida.');
    }

    protected function syncMembers(Role $role, array $userIds): void
    {
        CompanyUser::where('company_id', $role->company_id)
            ->where('role_id', $role->id)
            ->whereNotIn('user_id', $userIds)
            ->update(['role_id' => null]);

        foreach ($userIds as $userId) {
            CompanyUser::where('company_id', $role->company_id)
                ->where('user_id', $userId)
                ->update(['role_id' => $role->id]);
        }
    }
}
