<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Challenge;

class ChallengeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_challenge()
    {
        $challenge = Challenge::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/challenges', $challenge
        );

        $this->assertApiResponse($challenge);
    }

    /**
     * @test
     */
    public function test_read_challenge()
    {
        $challenge = Challenge::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/challenges/'.$challenge->id
        );

        $this->assertApiResponse($challenge->toArray());
    }

    /**
     * @test
     */
    public function test_update_challenge()
    {
        $challenge = Challenge::factory()->create();
        $editedChallenge = Challenge::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/challenges/'.$challenge->id,
            $editedChallenge
        );

        $this->assertApiResponse($editedChallenge);
    }

    /**
     * @test
     */
    public function test_delete_challenge()
    {
        $challenge = Challenge::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/challenges/'.$challenge->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/challenges/'.$challenge->id
        );

        $this->response->assertStatus(404);
    }
}
