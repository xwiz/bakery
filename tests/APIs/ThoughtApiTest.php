<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Thought;

class ThoughtApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_thought()
    {
        $thought = Thought::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/thoughts', $thought
        );

        $this->assertApiResponse($thought);
    }

    /**
     * @test
     */
    public function test_read_thought()
    {
        $thought = Thought::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/thoughts/'.$thought->id
        );

        $this->assertApiResponse($thought->toArray());
    }

    /**
     * @test
     */
    public function test_update_thought()
    {
        $thought = Thought::factory()->create();
        $editedThought = Thought::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/thoughts/'.$thought->id,
            $editedThought
        );

        $this->assertApiResponse($editedThought);
    }

    /**
     * @test
     */
    public function test_delete_thought()
    {
        $thought = Thought::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/thoughts/'.$thought->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/thoughts/'.$thought->id
        );

        $this->response->assertStatus(404);
    }
}
