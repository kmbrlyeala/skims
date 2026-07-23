<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_user_can_access_supplier_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'supplier',
        ]);

        $response = $this->actingAs($user)->get('/supplier');

        $response->assertOk();
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }
}
