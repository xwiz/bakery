<?php

namespace Tests\Repositories;

use App\Models\UserInterest;
use App\Repositories\UserInterestRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserInterestRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected UserInterestRepository $userInterestRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userInterestRepo = app(UserInterestRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_interest()
    {
        $userInterest = UserInterest::factory()->make()->toArray();

        $createdUserInterest = $this->userInterestRepo->create($userInterest);

        $createdUserInterest = $createdUserInterest->toArray();
        $this->assertArrayHasKey('id', $createdUserInterest);
        $this->assertNotNull($createdUserInterest['id'], 'Created UserInterest must have id specified');
        $this->assertNotNull(UserInterest::find($createdUserInterest['id']), 'UserInterest with given id must be in DB');
        $this->assertModelData($userInterest, $createdUserInterest);
    }

    /**
     * @test read
     */
    public function test_read_user_interest()
    {
        $userInterest = UserInterest::factory()->create();

        $dbUserInterest = $this->userInterestRepo->find($userInterest->id);

        $dbUserInterest = $dbUserInterest->toArray();
        $this->assertModelData($userInterest->toArray(), $dbUserInterest);
    }

    /**
     * @test update
     */
    public function test_update_user_interest()
    {
        $userInterest = UserInterest::factory()->create();
        $fakeUserInterest = UserInterest::factory()->make()->toArray();

        $updatedUserInterest = $this->userInterestRepo->update($fakeUserInterest, $userInterest->id);

        $this->assertModelData($fakeUserInterest, $updatedUserInterest->toArray());
        $dbUserInterest = $this->userInterestRepo->find($userInterest->id);
        $this->assertModelData($fakeUserInterest, $dbUserInterest->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_interest()
    {
        $userInterest = UserInterest::factory()->create();

        $resp = $this->userInterestRepo->delete($userInterest->id);

        $this->assertTrue($resp);
        $this->assertNull(UserInterest::find($userInterest->id), 'UserInterest should not exist in DB');
    }
}
