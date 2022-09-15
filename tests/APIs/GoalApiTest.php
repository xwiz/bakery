<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Goal;

class GoalApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_goal()
    {
        $goal = Goal::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/goals', $goal
        );

        $this->assertApiResponse($goal);
    }

    /**
     * @test
     */
    public function test_read_goal()
    {
        $goal = Goal::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/goals/'.$goal->id
        );

        $this->assertApiResponse($goal->toArray());
    }

    /**
     * @test
     */
    public function test_update_goal()
    {
        $goal = Goal::factory()->create();
        $editedGoal = Goal::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/goals/'.$goal->id,
            $editedGoal
        );

        $this->assertApiResponse($editedGoal);
    }

    /**
     * @test
     */
    public function test_delete_goal()
    {
        $goal = Goal::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/goals/'.$goal->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/goals/'.$goal->id
        );

        $this->response->assertStatus(404);
    }
}
