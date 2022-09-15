<?php

namespace Tests\Repositories;

use App\Models\Chat;
use App\Repositories\ChatRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ChatRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ChatRepository $chatRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->chatRepo = app(ChatRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_chat()
    {
        $chat = Chat::factory()->make()->toArray();

        $createdChat = $this->chatRepo->create($chat);

        $createdChat = $createdChat->toArray();
        $this->assertArrayHasKey('id', $createdChat);
        $this->assertNotNull($createdChat['id'], 'Created Chat must have id specified');
        $this->assertNotNull(Chat::find($createdChat['id']), 'Chat with given id must be in DB');
        $this->assertModelData($chat, $createdChat);
    }

    /**
     * @test read
     */
    public function test_read_chat()
    {
        $chat = Chat::factory()->create();

        $dbChat = $this->chatRepo->find($chat->id);

        $dbChat = $dbChat->toArray();
        $this->assertModelData($chat->toArray(), $dbChat);
    }

    /**
     * @test update
     */
    public function test_update_chat()
    {
        $chat = Chat::factory()->create();
        $fakeChat = Chat::factory()->make()->toArray();

        $updatedChat = $this->chatRepo->update($fakeChat, $chat->id);

        $this->assertModelData($fakeChat, $updatedChat->toArray());
        $dbChat = $this->chatRepo->find($chat->id);
        $this->assertModelData($fakeChat, $dbChat->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_chat()
    {
        $chat = Chat::factory()->create();

        $resp = $this->chatRepo->delete($chat->id);

        $this->assertTrue($resp);
        $this->assertNull(Chat::find($chat->id), 'Chat should not exist in DB');
    }
}
