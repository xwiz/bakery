<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\GoalCategory;

class GoalCategoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_goal_category()
    {
        $goalCategory = GoalCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/goal-categories', $goalCategory
        );

        $this->assertApiResponse($goalCategory);
    }

    /**
     * @test
     */
    public function test_read_goal_category()
    {
        $goalCategory = GoalCategory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/goal-categories/'.$goalCategory->id
        );

        $this->assertApiResponse($goalCategory->toArray());
    }

    /**
     * @test
     */
    public function test_update_goal_category()
    {
        $goalCategory = GoalCategory::factory()->create();
        $editedGoalCategory = GoalCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/goal-categories/'.$goalCategory->id,
            $editedGoalCategory
        );

        $this->assertApiResponse($editedGoalCategory);
    }

    /**
     * @test
     */
    public function test_delete_goal_category()
    {
        $goalCategory = GoalCategory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/goal-categories/'.$goalCategory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/goal-categories/'.$goalCategory->id
        );

        $this->response->assertStatus(404);
    }
}
