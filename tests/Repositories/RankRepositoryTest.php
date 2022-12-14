<?php

namespace Tests\Repositories;

use App\Models\Rank;
use App\Repositories\RankRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class RankRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected RankRepository $rankRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->rankRepo = app(RankRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_rank()
    {
        $rank = Rank::factory()->make()->toArray();

        $createdRank = $this->rankRepo->create($rank);

        $createdRank = $createdRank->toArray();
        $this->assertArrayHasKey('id', $createdRank);
        $this->assertNotNull($createdRank['id'], 'Created Rank must have id specified');
        $this->assertNotNull(Rank::find($createdRank['id']), 'Rank with given id must be in DB');
        $this->assertModelData($rank, $createdRank);
    }

    /**
     * @test read
     */
    public function test_read_rank()
    {
        $rank = Rank::factory()->create();

        $dbRank = $this->rankRepo->find($rank->id);

        $dbRank = $dbRank->toArray();
        $this->assertModelData($rank->toArray(), $dbRank);
    }

    /**
     * @test update
     */
    public function test_update_rank()
    {
        $rank = Rank::factory()->create();
        $fakeRank = Rank::factory()->make()->toArray();

        $updatedRank = $this->rankRepo->update($fakeRank, $rank->id);

        $this->assertModelData($fakeRank, $updatedRank->toArray());
        $dbRank = $this->rankRepo->find($rank->id);
        $this->assertModelData($fakeRank, $dbRank->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_rank()
    {
        $rank = Rank::factory()->create();

        $resp = $this->rankRepo->delete($rank->id);

        $this->assertTrue($resp);
        $this->assertNull(Rank::find($rank->id), 'Rank should not exist in DB');
    }
}
