<?php

namespace Tests\Repositories;

use App\Models\ThoughtCategory;
use App\Repositories\ThoughtCategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ThoughtCategoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ThoughtCategoryRepository $thoughtCategoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->thoughtCategoryRepo = app(ThoughtCategoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->make()->toArray();

        $createdThoughtCategory = $this->thoughtCategoryRepo->create($thoughtCategory);

        $createdThoughtCategory = $createdThoughtCategory->toArray();
        $this->assertArrayHasKey('id', $createdThoughtCategory);
        $this->assertNotNull($createdThoughtCategory['id'], 'Created ThoughtCategory must have id specified');
        $this->assertNotNull(ThoughtCategory::find($createdThoughtCategory['id']), 'ThoughtCategory with given id must be in DB');
        $this->assertModelData($thoughtCategory, $createdThoughtCategory);
    }

    /**
     * @test read
     */
    public function test_read_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->create();

        $dbThoughtCategory = $this->thoughtCategoryRepo->find($thoughtCategory->id);

        $dbThoughtCategory = $dbThoughtCategory->toArray();
        $this->assertModelData($thoughtCategory->toArray(), $dbThoughtCategory);
    }

    /**
     * @test update
     */
    public function test_update_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->create();
        $fakeThoughtCategory = ThoughtCategory::factory()->make()->toArray();

        $updatedThoughtCategory = $this->thoughtCategoryRepo->update($fakeThoughtCategory, $thoughtCategory->id);

        $this->assertModelData($fakeThoughtCategory, $updatedThoughtCategory->toArray());
        $dbThoughtCategory = $this->thoughtCategoryRepo->find($thoughtCategory->id);
        $this->assertModelData($fakeThoughtCategory, $dbThoughtCategory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_thought_category()
    {
        $thoughtCategory = ThoughtCategory::factory()->create();

        $resp = $this->thoughtCategoryRepo->delete($thoughtCategory->id);

        $this->assertTrue($resp);
        $this->assertNull(ThoughtCategory::find($thoughtCategory->id), 'ThoughtCategory should not exist in DB');
    }
}
