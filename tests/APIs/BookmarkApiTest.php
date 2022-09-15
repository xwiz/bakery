<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Bookmark;

class BookmarkApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_bookmark()
    {
        $bookmark = Bookmark::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/bookmarks', $bookmark
        );

        $this->assertApiResponse($bookmark);
    }

    /**
     * @test
     */
    public function test_read_bookmark()
    {
        $bookmark = Bookmark::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/bookmarks/'.$bookmark->id
        );

        $this->assertApiResponse($bookmark->toArray());
    }

    /**
     * @test
     */
    public function test_update_bookmark()
    {
        $bookmark = Bookmark::factory()->create();
        $editedBookmark = Bookmark::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/bookmarks/'.$bookmark->id,
            $editedBookmark
        );

        $this->assertApiResponse($editedBookmark);
    }

    /**
     * @test
     */
    public function test_delete_bookmark()
    {
        $bookmark = Bookmark::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/bookmarks/'.$bookmark->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/bookmarks/'.$bookmark->id
        );

        $this->response->assertStatus(404);
    }
}
