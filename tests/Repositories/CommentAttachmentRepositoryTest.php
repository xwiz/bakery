<?php

namespace Tests\Repositories;

use App\Models\CommentAttachment;
use App\Repositories\CommentAttachmentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CommentAttachmentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected CommentAttachmentRepository $commentAttachmentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->commentAttachmentRepo = app(CommentAttachmentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->make()->toArray();

        $createdCommentAttachment = $this->commentAttachmentRepo->create($commentAttachment);

        $createdCommentAttachment = $createdCommentAttachment->toArray();
        $this->assertArrayHasKey('id', $createdCommentAttachment);
        $this->assertNotNull($createdCommentAttachment['id'], 'Created CommentAttachment must have id specified');
        $this->assertNotNull(CommentAttachment::find($createdCommentAttachment['id']), 'CommentAttachment with given id must be in DB');
        $this->assertModelData($commentAttachment, $createdCommentAttachment);
    }

    /**
     * @test read
     */
    public function test_read_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->create();

        $dbCommentAttachment = $this->commentAttachmentRepo->find($commentAttachment->id);

        $dbCommentAttachment = $dbCommentAttachment->toArray();
        $this->assertModelData($commentAttachment->toArray(), $dbCommentAttachment);
    }

    /**
     * @test update
     */
    public function test_update_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->create();
        $fakeCommentAttachment = CommentAttachment::factory()->make()->toArray();

        $updatedCommentAttachment = $this->commentAttachmentRepo->update($fakeCommentAttachment, $commentAttachment->id);

        $this->assertModelData($fakeCommentAttachment, $updatedCommentAttachment->toArray());
        $dbCommentAttachment = $this->commentAttachmentRepo->find($commentAttachment->id);
        $this->assertModelData($fakeCommentAttachment, $dbCommentAttachment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_comment_attachment()
    {
        $commentAttachment = CommentAttachment::factory()->create();

        $resp = $this->commentAttachmentRepo->delete($commentAttachment->id);

        $this->assertTrue($resp);
        $this->assertNull(CommentAttachment::find($commentAttachment->id), 'CommentAttachment should not exist in DB');
    }
}
