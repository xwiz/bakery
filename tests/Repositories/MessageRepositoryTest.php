<?php

namespace Tests\Repositories;

use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MessageRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected MessageRepository $messageRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->messageRepo = app(MessageRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_message()
    {
        $message = Message::factory()->make()->toArray();

        $createdMessage = $this->messageRepo->create($message);

        $createdMessage = $createdMessage->toArray();
        $this->assertArrayHasKey('id', $createdMessage);
        $this->assertNotNull($createdMessage['id'], 'Created Message must have id specified');
        $this->assertNotNull(Message::find($createdMessage['id']), 'Message with given id must be in DB');
        $this->assertModelData($message, $createdMessage);
    }

    /**
     * @test read
     */
    public function test_read_message()
    {
        $message = Message::factory()->create();

        $dbMessage = $this->messageRepo->find($message->id);

        $dbMessage = $dbMessage->toArray();
        $this->assertModelData($message->toArray(), $dbMessage);
    }

    /**
     * @test update
     */
    public function test_update_message()
    {
        $message = Message::factory()->create();
        $fakeMessage = Message::factory()->make()->toArray();

        $updatedMessage = $this->messageRepo->update($fakeMessage, $message->id);

        $this->assertModelData($fakeMessage, $updatedMessage->toArray());
        $dbMessage = $this->messageRepo->find($message->id);
        $this->assertModelData($fakeMessage, $dbMessage->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_message()
    {
        $message = Message::factory()->create();

        $resp = $this->messageRepo->delete($message->id);

        $this->assertTrue($resp);
        $this->assertNull(Message::find($message->id), 'Message should not exist in DB');
    }
}
