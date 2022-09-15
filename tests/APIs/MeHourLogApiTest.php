<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MeHourLog;

class MeHourLogApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/me-hour-logs', $meHourLog
        );

        $this->assertApiResponse($meHourLog);
    }

    /**
     * @test
     */
    public function test_read_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/me-hour-logs/'.$meHourLog->id
        );

        $this->assertApiResponse($meHourLog->toArray());
    }

    /**
     * @test
     */
    public function test_update_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->create();
        $editedMeHourLog = MeHourLog::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/me-hour-logs/'.$meHourLog->id,
            $editedMeHourLog
        );

        $this->assertApiResponse($editedMeHourLog);
    }

    /**
     * @test
     */
    public function test_delete_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/me-hour-logs/'.$meHourLog->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/me-hour-logs/'.$meHourLog->id
        );

        $this->response->assertStatus(404);
    }
}
