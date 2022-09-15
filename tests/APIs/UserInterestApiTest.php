<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UserInterest;

class UserInterestApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_interest()
    {
        $userInterest = UserInterest::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/user-interests', $userInterest
        );

        $this->assertApiResponse($userInterest);
    }

    /**
     * @test
     */
    public function test_read_user_interest()
    {
        $userInterest = UserInterest::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/user-interests/'.$userInterest->id
        );

        $this->assertApiResponse($userInterest->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_interest()
    {
        $userInterest = UserInterest::factory()->create();
        $editedUserInterest = UserInterest::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/user-interests/'.$userInterest->id,
            $editedUserInterest
        );

        $this->assertApiResponse($editedUserInterest);
    }

    /**
     * @test
     */
    public function test_delete_user_interest()
    {
        $userInterest = UserInterest::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/user-interests/'.$userInterest->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/user-interests/'.$userInterest->id
        );

        $this->response->assertStatus(404);
    }
}
