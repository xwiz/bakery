<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Personality;

class PersonalityApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_personality()
    {
        $personality = Personality::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/personalities', $personality
        );

        $this->assertApiResponse($personality);
    }

    /**
     * @test
     */
    public function test_read_personality()
    {
        $personality = Personality::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/personalities/'.$personality->id
        );

        $this->assertApiResponse($personality->toArray());
    }

    /**
     * @test
     */
    public function test_update_personality()
    {
        $personality = Personality::factory()->create();
        $editedPersonality = Personality::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/personalities/'.$personality->id,
            $editedPersonality
        );

        $this->assertApiResponse($editedPersonality);
    }

    /**
     * @test
     */
    public function test_delete_personality()
    {
        $personality = Personality::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/personalities/'.$personality->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/personalities/'.$personality->id
        );

        $this->response->assertStatus(404);
    }
}
