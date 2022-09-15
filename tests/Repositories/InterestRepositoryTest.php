<?php

namespace Tests\Repositories;

use App\Models\Interest;
use App\Repositories\InterestRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class InterestRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected InterestRepository $interestRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->interestRepo = app(InterestRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_interest()
    {
        $interest = Interest::factory()->make()->toArray();

        $createdInterest = $this->interestRepo->create($interest);

        $createdInterest = $createdInterest->toArray();
        $this->assertArrayHasKey('id', $createdInterest);
        $this->assertNotNull($createdInterest['id'], 'Created Interest must have id specified');
        $this->assertNotNull(Interest::find($createdInterest['id']), 'Interest with given id must be in DB');
        $this->assertModelData($interest, $createdInterest);
    }

    /**
     * @test read
     */
    public function test_read_interest()
    {
        $interest = Interest::factory()->create();

        $dbInterest = $this->interestRepo->find($interest->id);

        $dbInterest = $dbInterest->toArray();
        $this->assertModelData($interest->toArray(), $dbInterest);
    }

    /**
     * @test update
     */
    public function test_update_interest()
    {
        $interest = Interest::factory()->create();
        $fakeInterest = Interest::factory()->make()->toArray();

        $updatedInterest = $this->interestRepo->update($fakeInterest, $interest->id);

        $this->assertModelData($fakeInterest, $updatedInterest->toArray());
        $dbInterest = $this->interestRepo->find($interest->id);
        $this->assertModelData($fakeInterest, $dbInterest->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_interest()
    {
        $interest = Interest::factory()->create();

        $resp = $this->interestRepo->delete($interest->id);

        $this->assertTrue($resp);
        $this->assertNull(Interest::find($interest->id), 'Interest should not exist in DB');
    }
}
