<?php

namespace Tests\Repositories;

use App\Models\MessageAttachment;
use App\Repositories\MessageAttachmentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MessageAttachmentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected MessageAttachmentRepository $messageAttachmentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->messageAttachmentRepo = app(MessageAttachmentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->make()->toArray();

        $createdMessageAttachment = $this->messageAttachmentRepo->create($messageAttachment);

        $createdMessageAttachment = $createdMessageAttachment->toArray();
        $this->assertArrayHasKey('id', $createdMessageAttachment);
        $this->assertNotNull($createdMessageAttachment['id'], 'Created MessageAttachment must have id specified');
        $this->assertNotNull(MessageAttachment::find($createdMessageAttachment['id']), 'MessageAttachment with given id must be in DB');
        $this->assertModelData($messageAttachment, $createdMessageAttachment);
    }

    /**
     * @test read
     */
    public function test_read_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->create();

        $dbMessageAttachment = $this->messageAttachmentRepo->find($messageAttachment->id);

        $dbMessageAttachment = $dbMessageAttachment->toArray();
        $this->assertModelData($messageAttachment->toArray(), $dbMessageAttachment);
    }

    /**
     * @test update
     */
    public function test_update_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->create();
        $fakeMessageAttachment = MessageAttachment::factory()->make()->toArray();

        $updatedMessageAttachment = $this->messageAttachmentRepo->update($fakeMessageAttachment, $messageAttachment->id);

        $this->assertModelData($fakeMessageAttachment, $updatedMessageAttachment->toArray());
        $dbMessageAttachment = $this->messageAttachmentRepo->find($messageAttachment->id);
        $this->assertModelData($fakeMessageAttachment, $dbMessageAttachment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_message_attachment()
    {
        $messageAttachment = MessageAttachment::factory()->create();

        $resp = $this->messageAttachmentRepo->delete($messageAttachment->id);

        $this->assertTrue($resp);
        $this->assertNull(MessageAttachment::find($messageAttachment->id), 'MessageAttachment should not exist in DB');
    }
}
