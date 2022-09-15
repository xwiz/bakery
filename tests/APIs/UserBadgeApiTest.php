<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UserBadge;

class UserBadgeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_badge()
    {
        $userBadge = UserBadge::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/user-badges', $userBadge
        );

        $this->assertApiResponse($userBadge);
    }

    /**
     * @test
     */
    public function test_read_user_badge()
    {
        $userBadge = UserBadge::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/user-badges/'.$userBadge->id
        );

        $this->assertApiResponse($userBadge->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_badge()
    {
        $userBadge = UserBadge::factory()->create();
        $editedUserBadge = UserBadge::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/user-badges/'.$userBadge->id,
            $editedUserBadge
        );

        $this->assertApiResponse($editedUserBadge);
    }

    /**
     * @test
     */
    public function test_delete_user_badge()
    {
        $userBadge = UserBadge::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/user-badges/'.$userBadge->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/user-badges/'.$userBadge->id
        );

        $this->response->assertStatus(404);
    }
}
