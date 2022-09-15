<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Chat;

class ChatApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_chat()
    {
        $chat = Chat::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/chats', $chat
        );

        $this->assertApiResponse($chat);
    }

    /**
     * @test
     */
    public function test_read_chat()
    {
        $chat = Chat::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/chats/'.$chat->id
        );

        $this->assertApiResponse($chat->toArray());
    }

    /**
     * @test
     */
    public function test_update_chat()
    {
        $chat = Chat::factory()->create();
        $editedChat = Chat::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/chats/'.$chat->id,
            $editedChat
        );

        $this->assertApiResponse($editedChat);
    }

    /**
     * @test
     */
    public function test_delete_chat()
    {
        $chat = Chat::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/chats/'.$chat->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/chats/'.$chat->id
        );

        $this->response->assertStatus(404);
    }
}
