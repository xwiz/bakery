<?php

namespace Tests\Repositories;

use App\Models\Attachment;
use App\Repositories\AttachmentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AttachmentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected AttachmentRepository $attachmentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->attachmentRepo = app(AttachmentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_attachment()
    {
        $attachment = Attachment::factory()->make()->toArray();

        $createdAttachment = $this->attachmentRepo->create($attachment);

        $createdAttachment = $createdAttachment->toArray();
        $this->assertArrayHasKey('id', $createdAttachment);
        $this->assertNotNull($createdAttachment['id'], 'Created Attachment must have id specified');
        $this->assertNotNull(Attachment::find($createdAttachment['id']), 'Attachment with given id must be in DB');
        $this->assertModelData($attachment, $createdAttachment);
    }

    /**
     * @test read
     */
    public function test_read_attachment()
    {
        $attachment = Attachment::factory()->create();

        $dbAttachment = $this->attachmentRepo->find($attachment->id);

        $dbAttachment = $dbAttachment->toArray();
        $this->assertModelData($attachment->toArray(), $dbAttachment);
    }

    /**
     * @test update
     */
    public function test_update_attachment()
    {
        $attachment = Attachment::factory()->create();
        $fakeAttachment = Attachment::factory()->make()->toArray();

        $updatedAttachment = $this->attachmentRepo->update($fakeAttachment, $attachment->id);

        $this->assertModelData($fakeAttachment, $updatedAttachment->toArray());
        $dbAttachment = $this->attachmentRepo->find($attachment->id);
        $this->assertModelData($fakeAttachment, $dbAttachment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_attachment()
    {
        $attachment = Attachment::factory()->create();

        $resp = $this->attachmentRepo->delete($attachment->id);

        $this->assertTrue($resp);
        $this->assertNull(Attachment::find($attachment->id), 'Attachment should not exist in DB');
    }
}
