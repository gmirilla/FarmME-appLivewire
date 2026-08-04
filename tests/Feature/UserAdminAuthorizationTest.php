<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_change_another_users_role(): void
    {
        $inspector = User::factory()->create(['roles' => 'INSPECTOR']);
        $target = User::factory()->create(['roles' => 'INSPECTOR']);

        $response = $this->actingAs($inspector)->post('/user_update', [
            'userid' => $target->id,
            'userrole' => 'ADMINISTRATOR',
        ]);

        $response->assertRedirect(route('unauthorized'));
        $this->assertSame('INSPECTOR', $target->fresh()->roles);
    }

    public function test_non_admin_cannot_reset_another_users_password(): void
    {
        $inspector = User::factory()->create(['roles' => 'INSPECTOR']);
        $target = User::factory()->create(['roles' => 'INSPECTOR']);
        $originalPassword = $target->password;

        $response = $this->actingAs($inspector)->post('/user_pwd', [
            'uid' => $target->id,
            'password' => 'newpassword123',
        ]);

        $response->assertRedirect(route('unauthorized'));
        $this->assertSame($originalPassword, $target->fresh()->password);
    }

    public function test_admin_can_change_another_users_role(): void
    {
        $admin = User::factory()->create(['roles' => 'ADMINISTRATOR']);
        $target = User::factory()->create(['roles' => 'INSPECTOR']);

        $response = $this->actingAs($admin)->post('/user_update', [
            'userid' => $target->id,
            'userrole' => 'ADMINISTRATOR',
        ]);

        $response->assertOk();
        $this->assertSame('ADMINISTRATOR', $target->fresh()->roles);
    }
}
