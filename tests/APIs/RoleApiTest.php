<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Role;

class RoleApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_role()
    {
        $role = Role::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/roles', $role
        );

        $this->assertApiResponse($role);
    }

    /**
     * @test
     */
    public function test_read_role()
    {
        $role = Role::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/roles/'.$role->id
        );

        $this->assertApiResponse($role->toArray());
    }

    /**
     * @test
     */
    public function test_update_role()
    {
        $role = Role::factory()->create();
        $editedRole = Role::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/roles/'.$role->id,
            $editedRole
        );

        $this->assertApiResponse($editedRole);
    }

}
