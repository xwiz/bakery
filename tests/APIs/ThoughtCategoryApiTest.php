<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ThoughtCategory;

class ThoughtCategoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/thought-categories', $thoughtCategory
        );

        $this->assertApiResponse($thoughtCategory);
    }

    /**
     * @test
     */
    public function test_read_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/thought-categories/'.$thoughtCategory->id
        );

        $this->assertApiResponse($thoughtCategory->toArray());
    }

    /**
     * @test
     */
    public function test_update_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->create();
        $editedThoughtCategory = ThoughtCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/thought-categories/'.$thoughtCategory->id,
            $editedThoughtCategory
        );

        $this->assertApiResponse($editedThoughtCategory);
    }

    /**
     * @test
     */
    public function test_delete_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/thought-categories/'.$thoughtCategory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/thought-categories/'.$thoughtCategory->id
        );

        $this->response->assertStatus(404);
    }
}
