<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Reaction;

class ReactionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_reaction()
    {
        $reaction = Reaction::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/reactions', $reaction
        );

        $this->assertApiResponse($reaction);
    }

    /**
     * @test
     */
    public function test_read_reaction()
    {
        $reaction = Reaction::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/reactions/'.$reaction->id
        );

        $this->assertApiResponse($reaction->toArray());
    }

    /**
     * @test
     */
    public function test_update_reaction()
    {
        $reaction = Reaction::factory()->create();
        $editedReaction = Reaction::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/reactions/'.$reaction->id,
            $editedReaction
        );

        $this->assertApiResponse($editedReaction);
    }

    /**
     * @test
     */
    public function test_delete_reaction()
    {
        $reaction = Reaction::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/reactions/'.$reaction->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/reactions/'.$reaction->id
        );

        $this->response->assertStatus(404);
    }
}
