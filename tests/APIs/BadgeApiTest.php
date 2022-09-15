<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Badge;

class BadgeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_badge()
    {
        $badge = Badge::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/badges', $badge
        );

        $this->assertApiResponse($badge);
    }

    /**
     * @test
     */
    public function test_read_badge()
    {
        $badge = Badge::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/badges/'.$badge->id
        );

        $this->assertApiResponse($badge->toArray());
    }

    /**
     * @test
     */
    public function test_update_badge()
    {
        $badge = Badge::factory()->create();
        $editedBadge = Badge::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/badges/'.$badge->id,
            $editedBadge
        );

        $this->assertApiResponse($editedBadge);
    }

    /**
     * @test
     */
    public function test_delete_badge()
    {
        $badge = Badge::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/badges/'.$badge->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/badges/'.$badge->id
        );

        $this->response->assertStatus(404);
    }
}
