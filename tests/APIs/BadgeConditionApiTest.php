<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\BadgeCondition;

class BadgeConditionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/badge-conditions', $badgeCondition
        );

        $this->assertApiResponse($badgeCondition);
    }

    /**
     * @test
     */
    public function test_read_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/badge-conditions/'.$badgeCondition->id
        );

        $this->assertApiResponse($badgeCondition->toArray());
    }

    /**
     * @test
     */
    public function test_update_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->create();
        $editedBadgeCondition = BadgeCondition::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/badge-conditions/'.$badgeCondition->id,
            $editedBadgeCondition
        );

        $this->assertApiResponse($editedBadgeCondition);
    }

    /**
     * @test
     */
    public function test_delete_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/badge-conditions/'.$badgeCondition->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/badge-conditions/'.$badgeCondition->id
        );

        $this->response->assertStatus(404);
    }
}
