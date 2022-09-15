<?php

namespace Tests\Repositories;

use App\Models\AccbPartner;
use App\Repositories\AccbPartnerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AccbPartnerRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected AccbPartnerRepository $accbPartnerRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->accbPartnerRepo = app(AccbPartnerRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->make()->toArray();

        $createdAccbPartner = $this->accbPartnerRepo->create($accbPartner);

        $createdAccbPartner = $createdAccbPartner->toArray();
        $this->assertArrayHasKey('id', $createdAccbPartner);
        $this->assertNotNull($createdAccbPartner['id'], 'Created AccbPartner must have id specified');
        $this->assertNotNull(AccbPartner::find($createdAccbPartner['id']), 'AccbPartner with given id must be in DB');
        $this->assertModelData($accbPartner, $createdAccbPartner);
    }

    /**
     * @test read
     */
    public function test_read_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->create();

        $dbAccbPartner = $this->accbPartnerRepo->find($accbPartner->id);

        $dbAccbPartner = $dbAccbPartner->toArray();
        $this->assertModelData($accbPartner->toArray(), $dbAccbPartner);
    }

    /**
     * @test update
     */
    public function test_update_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->create();
        $fakeAccbPartner = AccbPartner::factory()->make()->toArray();

        $updatedAccbPartner = $this->accbPartnerRepo->update($fakeAccbPartner, $accbPartner->id);

        $this->assertModelData($fakeAccbPartner, $updatedAccbPartner->toArray());
        $dbAccbPartner = $this->accbPartnerRepo->find($accbPartner->id);
        $this->assertModelData($fakeAccbPartner, $dbAccbPartner->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->create();

        $resp = $this->accbPartnerRepo->delete($accbPartner->id);

        $this->assertTrue($resp);
        $this->assertNull(AccbPartner::find($accbPartner->id), 'AccbPartner should not exist in DB');
    }
}
