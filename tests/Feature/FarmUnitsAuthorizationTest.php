<?php

namespace Tests\Feature;

use App\Models\farm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FarmUnitsAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeFarm(?int $inspectorId = null): farm
    {
        return farm::create([
            'farmname' => 'Test Farm',
            'community' => 'Test Community',
            'farmcode' => 'F-'.uniqid(),
            'inspectorid' => $inspectorId,
        ]);
    }

    public function test_inspector_cannot_view_units_of_a_farm_assigned_to_someone_else(): void
    {
        $owner = User::factory()->create(['roles' => 'INSPECTOR']);
        $otherInspector = User::factory()->create(['roles' => 'INSPECTOR']);
        $farm = $this->makeFarm($owner->id);

        $response = $this->actingAs($otherInspector)->get('/fu/list?fid='.$farm->id);

        $response->assertRedirect(route('unauthorized'));
    }

    public function test_inspector_can_view_units_of_their_own_farm(): void
    {
        $owner = User::factory()->create(['roles' => 'INSPECTOR']);
        $farm = $this->makeFarm($owner->id);

        $response = $this->actingAs($owner)->get('/fu/list?fid='.$farm->id);

        $response->assertOk();
    }

    public function test_administrator_can_view_units_of_any_farm(): void
    {
        $owner = User::factory()->create(['roles' => 'INSPECTOR']);
        $admin = User::factory()->create(['roles' => 'ADMINISTRATOR']);
        $farm = $this->makeFarm($owner->id);

        $response = $this->actingAs($admin)->get('/fu/list?fid='.$farm->id);

        $response->assertOk();
    }
}
