<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CommentAttachment;

class CommentAttachmentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/comment-attachments', $commentAttachment
        );

        $this->assertApiResponse($commentAttachment);
    }

    /**
     * @test
     */
    public function test_read_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/comment-attachments/'.$commentAttachment->id
        );

        $this->assertApiResponse($commentAttachment->toArray());
    }

    /**
     * @test
     */
    public function test_update_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->create();
        $editedCommentAttachment = CommentAttachment::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/comment-attachments/'.$commentAttachment->id,
            $editedCommentAttachment
        );

        $this->assertApiResponse($editedCommentAttachment);
    }

    /**
     * @test
     */
    public function test_delete_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/comment-attachments/'.$commentAttachment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/comment-attachments/'.$commentAttachment->id
        );

        $this->response->assertStatus(404);
    }
}
