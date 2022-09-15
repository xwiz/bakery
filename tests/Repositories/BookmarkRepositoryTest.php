<?php

namespace Tests\Repositories;

use App\Models\Bookmark;
use App\Repositories\BookmarkRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BookmarkRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected BookmarkRepository $bookmarkRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->bookmarkRepo = app(BookmarkRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_bookmark()
    {
        $bookmark = Bookmark::factory()->make()->toArray();

        $createdBookmark = $this->bookmarkRepo->create($bookmark);

        $createdBookmark = $createdBookmark->toArray();
        $this->assertArrayHasKey('id', $createdBookmark);
        $this->assertNotNull($createdBookmark['id'], 'Created Bookmark must have id specified');
        $this->assertNotNull(Bookmark::find($createdBookmark['id']), 'Bookmark with given id must be in DB');
        $this->assertModelData($bookmark, $createdBookmark);
    }

    /**
     * @test read
     */
    public function test_read_bookmark()
    {
        $bookmark = Bookmark::factory()->create();

        $dbBookmark = $this->bookmarkRepo->find($bookmark->id);

        $dbBookmark = $dbBookmark->toArray();
        $this->assertModelData($bookmark->toArray(), $dbBookmark);
    }

    /**
     * @test update
     */
    public function test_update_bookmark()
    {
        $bookmark = Bookmark::factory()->create();
        $fakeBookmark = Bookmark::factory()->make()->toArray();

        $updatedBookmark = $this->bookmarkRepo->update($fakeBookmark, $bookmark->id);

        $this->assertModelData($fakeBookmark, $updatedBookmark->toArray());
        $dbBookmark = $this->bookmarkRepo->find($bookmark->id);
        $this->assertModelData($fakeBookmark, $dbBookmark->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_bookmark()
    {
        $bookmark = Bookmark::factory()->create();

        $resp = $this->bookmarkRepo->delete($bookmark->id);

        $this->assertTrue($resp);
        $this->assertNull(Bookmark::find($bookmark->id), 'Bookmark should not exist in DB');
    }
}
