<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MessageAttachment;

class MessageAttachmentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/message-attachments', $messageAttachment
        );

        $this->assertApiResponse($messageAttachment);
    }

    /**
     * @test
     */
    public function test_read_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/message-attachments/'.$messageAttachment->id
        );

        $this->assertApiResponse($messageAttachment->toArray());
    }

    /**
     * @test
     */
    public function test_update_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->create();
        $editedMessageAttachment = MessageAttachment::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/message-attachments/'.$messageAttachment->id,
            $editedMessageAttachment
        );

        $this->assertApiResponse($editedMessageAttachment);
    }

    /**
     * @test
     */
    public function test_delete_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/message-attachments/'.$messageAttachment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/message-attachments/'.$messageAttachment->id
        );

        $this->response->assertStatus(404);
    }
}
