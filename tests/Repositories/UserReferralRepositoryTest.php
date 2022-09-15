<?php

namespace Tests\Repositories;

use App\Models\UserReferral;
use App\Repositories\UserReferralRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserReferralRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected UserReferralRepository $userReferralRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userReferralRepo = app(UserReferralRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_referral()
    {
        $userReferral = UserReferral::factory()->make()->toArray();

        $createdUserReferral = $this->userReferralRepo->create($userReferral);

        $createdUserReferral = $createdUserReferral->toArray();
        $this->assertArrayHasKey('id', $createdUserReferral);
        $this->assertNotNull($createdUserReferral['id'], 'Created UserReferral must have id specified');
        $this->assertNotNull(UserReferral::find($createdUserReferral['id']), 'UserReferral with given id must be in DB');
        $this->assertModelData($userReferral, $createdUserReferral);
    }

    /**
     * @test read
     */
    public function test_read_user_referral()
    {
        $userReferral = UserReferral::factory()->create();

        $dbUserReferral = $this->userReferralRepo->find($userReferral->id);

        $dbUserReferral = $dbUserReferral->toArray();
        $this->assertModelData($userReferral->toArray(), $dbUserReferral);
    }

    /**
     * @test update
     */
    public function test_update_user_referral()
    {
        $userReferral = UserReferral::factory()->create();
        $fakeUserReferral = UserReferral::factory()->make()->toArray();

        $updatedUserReferral = $this->userReferralRepo->update($fakeUserReferral, $userReferral->id);

        $this->assertModelData($fakeUserReferral, $updatedUserReferral->toArray());
        $dbUserReferral = $this->userReferralRepo->find($userReferral->id);
        $this->assertModelData($fakeUserReferral, $dbUserReferral->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_referral()
    {
        $userReferral = UserReferral::factory()->create();

        $resp = $this->userReferralRepo->delete($userReferral->id);

        $this->assertTrue($resp);
        $this->assertNull(UserReferral::find($userReferral->id), 'UserReferral should not exist in DB');
    }
}
