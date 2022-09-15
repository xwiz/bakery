<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\JolliTag;

class JolliTagApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/jolli-tags', $jolliTag
        );

        $this->assertApiResponse($jolliTag);
    }

    /**
     * @test
     */
    public function test_read_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-tags/'.$jolliTag->id
        );

        $this->assertApiResponse($jolliTag->toArray());
    }

    /**
     * @test
     */
    public function test_update_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->create();
        $editedJolliTag = JolliTag::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/jolli-tags/'.$jolliTag->id,
            $editedJolliTag
        );

        $this->assertApiResponse($editedJolliTag);
    }

    /**
     * @test
     */
    public function test_delete_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/jolli-tags/'.$jolliTag->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-tags/'.$jolliTag->id
        );

        $this->response->assertStatus(404);
    }
}
