<?php

namespace Tests\Repositories;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CommentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected CommentRepository $commentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->commentRepo = app(CommentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_comment()
    {
        $comment = Comment::factory()->make()->toArray();

        $createdComment = $this->commentRepo->create($comment);

        $createdComment = $createdComment->toArray();
        $this->assertArrayHasKey('id', $createdComment);
        $this->assertNotNull($createdComment['id'], 'Created Comment must have id specified');
        $this->assertNotNull(Comment::find($createdComment['id']), 'Comment with given id must be in DB');
        $this->assertModelData($comment, $createdComment);
    }

    /**
     * @test read
     */
    public function test_read_comment()
    {
        $comment = Comment::factory()->create();

        $dbComment = $this->commentRepo->find($comment->id);

        $dbComment = $dbComment->toArray();
        $this->assertModelData($comment->toArray(), $dbComment);
    }

    /**
     * @test update
     */
    public function test_update_comment()
    {
        $comment = Comment::factory()->create();
        $fakeComment = Comment::factory()->make()->toArray();

        $updatedComment = $this->commentRepo->update($fakeComment, $comment->id);

        $this->assertModelData($fakeComment, $updatedComment->toArray());
        $dbComment = $this->commentRepo->find($comment->id);
        $this->assertModelData($fakeComment, $dbComment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_comment()
    {
        $comment = Comment::factory()->create();

        $resp = $this->commentRepo->delete($comment->id);

        $this->assertTrue($resp);
        $this->assertNull(Comment::find($comment->id), 'Comment should not exist in DB');
    }
}
