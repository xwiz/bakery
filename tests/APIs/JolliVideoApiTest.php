<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\JolliVideo;

class JolliVideoApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/jolli-videos', $jolliVideo
        );

        $this->assertApiResponse($jolliVideo);
    }

    /**
     * @test
     */
    public function test_read_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-videos/'.$jolliVideo->id
        );

        $this->assertApiResponse($jolliVideo->toArray());
    }

    /**
     * @test
     */
    public function test_update_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->create();
        $editedJolliVideo = JolliVideo::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/jolli-videos/'.$jolliVideo->id,
            $editedJolliVideo
        );

        $this->assertApiResponse($editedJolliVideo);
    }

    /**
     * @test
     */
    public function test_delete_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/jolli-videos/'.$jolliVideo->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-videos/'.$jolliVideo->id
        );

        $this->response->assertStatus(404);
    }
}
