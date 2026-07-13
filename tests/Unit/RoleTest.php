<?php

namespace Tests\Unit;

use App\Domains\Company\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_owner_role_with_wildcard_grants_any_capability(): void
    {
        $role = new Role(['capabilities' => ['*']]);

        $this->assertTrue($role->hasCapability('config.manage'));
        $this->assertTrue($role->hasCapability('anything.else'));
    }

    public function test_role_without_capability_denies_it(): void
    {
        $role = new Role(['capabilities' => ['crm.view']]);

        $this->assertTrue($role->hasCapability('crm.view'));
        $this->assertFalse($role->hasCapability('config.manage'));
    }

    public function test_role_with_null_capabilities_denies_everything(): void
    {
        $role = new Role(['capabilities' => null]);

        $this->assertFalse($role->hasCapability('crm.view'));
    }
}
