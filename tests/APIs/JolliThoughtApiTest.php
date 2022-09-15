<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\JolliThought;

class JolliThoughtApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/jolli-thoughts', $jolliThought
        );

        $this->assertApiResponse($jolliThought);
    }

    /**
     * @test
     */
    public function test_read_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-thoughts/'.$jolliThought->id
        );

        $this->assertApiResponse($jolliThought->toArray());
    }

    /**
     * @test
     */
    public function test_update_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->create();
        $editedJolliThought = JolliThought::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/jolli-thoughts/'.$jolliThought->id,
            $editedJolliThought
        );

        $this->assertApiResponse($editedJolliThought);
    }

    /**
     * @test
     */
    public function test_delete_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/jolli-thoughts/'.$jolliThought->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-thoughts/'.$jolliThought->id
        );

        $this->response->assertStatus(404);
    }
}
