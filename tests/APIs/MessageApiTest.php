<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Message;

class MessageApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_message()
    {
        $message = Message::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/messages', $message
        );

        $this->assertApiResponse($message);
    }

    /**
     * @test
     */
    public function test_read_message()
    {
        $message = Message::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/messages/'.$message->id
        );

        $this->assertApiResponse($message->toArray());
    }

    /**
     * @test
     */
    public function test_update_message()
    {
        $message = Message::factory()->create();
        $editedMessage = Message::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/messages/'.$message->id,
            $editedMessage
        );

        $this->assertApiResponse($editedMessage);
    }

    /**
     * @test
     */
    public function test_delete_message()
    {
        $message = Message::factory()->create();

        $this->response = $this->json(
            'DELETE',
            '/api/v1/messages/'.$message->id
        );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/messages/'.$message->id
        );

        $this->response->assertStatus(404);
    }
}
