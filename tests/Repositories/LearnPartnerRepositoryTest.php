<?php

namespace Tests\Repositories;

use App\Models\LearnPartner;
use App\Repositories\LearnPartnerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class LearnPartnerRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected LearnPartnerRepository $learnPartnerRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->learnPartnerRepo = app(LearnPartnerRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->make()->toArray();

        $createdLearnPartner = $this->learnPartnerRepo->create($learnPartner);

        $createdLearnPartner = $createdLearnPartner->toArray();
        $this->assertArrayHasKey('id', $createdLearnPartner);
        $this->assertNotNull($createdLearnPartner['id'], 'Created LearnPartner must have id specified');
        $this->assertNotNull(LearnPartner::find($createdLearnPartner['id']), 'LearnPartner with given id must be in DB');
        $this->assertModelData($learnPartner, $createdLearnPartner);
    }

    /**
     * @test read
     */
    public function test_read_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->create();

        $dbLearnPartner = $this->learnPartnerRepo->find($learnPartner->id);

        $dbLearnPartner = $dbLearnPartner->toArray();
        $this->assertModelData($learnPartner->toArray(), $dbLearnPartner);
    }

    /**
     * @test update
     */
    public function test_update_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->create();
        $fakeLearnPartner = LearnPartner::factory()->make()->toArray();

        $updatedLearnPartner = $this->learnPartnerRepo->update($fakeLearnPartner, $learnPartner->id);

        $this->assertModelData($fakeLearnPartner, $updatedLearnPartner->toArray());
        $dbLearnPartner = $this->learnPartnerRepo->find($learnPartner->id);
        $this->assertModelData($fakeLearnPartner, $dbLearnPartner->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->create();

        $resp = $this->learnPartnerRepo->delete($learnPartner->id);

        $this->assertTrue($resp);
        $this->assertNull(LearnPartner::find($learnPartner->id), 'LearnPartner should not exist in DB');
    }
}
