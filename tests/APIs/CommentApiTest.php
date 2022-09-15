<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Comment;

class CommentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_comment()
    {
        $comment = Comment::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/comments', $comment
        );

        $this->assertApiResponse($comment);
    }

    /**
     * @test
     */
    public function test_read_comment()
    {
        $comment = Comment::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/comments/'.$comment->id
        );

        $this->assertApiResponse($comment->toArray());
    }

    /**
     * @test
     */
    public function test_update_comment()
    {
        $comment = Comment::factory()->create();
        $editedComment = Comment::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/comments/'.$comment->id,
            $editedComment
        );

        $this->assertApiResponse($editedComment);
    }

    /**
     * @test
     */
    public function test_delete_comment()
    {
        $comment = Comment::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/comments/'.$comment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/comments/'.$comment->id
        );

        $this->response->assertStatus(404);
    }
}
