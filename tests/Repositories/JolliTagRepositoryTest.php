<?php

namespace Tests\Repositories;

use App\Models\JolliTag;
use App\Repositories\JolliTagRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class JolliTagRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected JolliTagRepository $jolliTagRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->jolliTagRepo = app(JolliTagRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->make()->toArray();

        $createdJolliTag = $this->jolliTagRepo->create($jolliTag);

        $createdJolliTag = $createdJolliTag->toArray();
        $this->assertArrayHasKey('id', $createdJolliTag);
        $this->assertNotNull($createdJolliTag['id'], 'Created JolliTag must have id specified');
        $this->assertNotNull(JolliTag::find($createdJolliTag['id']), 'JolliTag with given id must be in DB');
        $this->assertModelData($jolliTag, $createdJolliTag);
    }

    /**
     * @test read
     */
    public function test_read_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->create();

        $dbJolliTag = $this->jolliTagRepo->find($jolliTag->id);

        $dbJolliTag = $dbJolliTag->toArray();
        $this->assertModelData($jolliTag->toArray(), $dbJolliTag);
    }

    /**
     * @test update
     */
    public function test_update_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->create();
        $fakeJolliTag = JolliTag::factory()->make()->toArray();

        $updatedJolliTag = $this->jolliTagRepo->update($fakeJolliTag, $jolliTag->id);

        $this->assertModelData($fakeJolliTag, $updatedJolliTag->toArray());
        $dbJolliTag = $this->jolliTagRepo->find($jolliTag->id);
        $this->assertModelData($fakeJolliTag, $dbJolliTag->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_jolli_tag()
    {
        $jolliTag = JolliTag::factory()->create();

        $resp = $this->jolliTagRepo->delete($jolliTag->id);

        $this->assertTrue($resp);
        $this->assertNull(JolliTag::find($jolliTag->id), 'JolliTag should not exist in DB');
    }
}
