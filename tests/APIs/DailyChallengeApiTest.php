<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DailyChallenge;

class DailyChallengeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/daily-challenges', $dailyChallenge
        );

        $this->assertApiResponse($dailyChallenge);
    }

    /**
     * @test
     */
    public function test_read_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/daily-challenges/'.$dailyChallenge->id
        );

        $this->assertApiResponse($dailyChallenge->toArray());
    }

    /**
     * @test
     */
    public function test_update_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->create();
        $editedDailyChallenge = DailyChallenge::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/daily-challenges/'.$dailyChallenge->id,
            $editedDailyChallenge
        );

        $this->assertApiResponse($editedDailyChallenge);
    }

    /**
     * @test
     */
    public function test_delete_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/daily-challenges/'.$dailyChallenge->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/daily-challenges/'.$dailyChallenge->id
        );

        $this->response->assertStatus(404);
    }
}
