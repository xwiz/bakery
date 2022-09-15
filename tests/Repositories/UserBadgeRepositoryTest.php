<?php

namespace Tests\Repositories;

use App\Models\UserBadge;
use App\Repositories\UserBadgeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserBadgeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected UserBadgeRepository $userBadgeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userBadgeRepo = app(UserBadgeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_badge()
    {
        $userBadge = UserBadge::factory()->make()->toArray();

        $createdUserBadge = $this->userBadgeRepo->create($userBadge);

        $createdUserBadge = $createdUserBadge->toArray();
        $this->assertArrayHasKey('id', $createdUserBadge);
        $this->assertNotNull($createdUserBadge['id'], 'Created UserBadge must have id specified');
        $this->assertNotNull(UserBadge::find($createdUserBadge['id']), 'UserBadge with given id must be in DB');
        $this->assertModelData($userBadge, $createdUserBadge);
    }

    /**
     * @test read
     */
    public function test_read_user_badge()
    {
        $userBadge = UserBadge::factory()->create();

        $dbUserBadge = $this->userBadgeRepo->find($userBadge->id);

        $dbUserBadge = $dbUserBadge->toArray();
        $this->assertModelData($userBadge->toArray(), $dbUserBadge);
    }

    /**
     * @test update
     */
    public function test_update_user_badge()
    {
        $userBadge = UserBadge::factory()->create();
        $fakeUserBadge = UserBadge::factory()->make()->toArray();

        $updatedUserBadge = $this->userBadgeRepo->update($fakeUserBadge, $userBadge->id);

        $this->assertModelData($fakeUserBadge, $updatedUserBadge->toArray());
        $dbUserBadge = $this->userBadgeRepo->find($userBadge->id);
        $this->assertModelData($fakeUserBadge, $dbUserBadge->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_badge()
    {
        $userBadge = UserBadge::factory()->create();

        $resp = $this->userBadgeRepo->delete($userBadge->id);

        $this->assertTrue($resp);
        $this->assertNull(UserBadge::find($userBadge->id), 'UserBadge should not exist in DB');
    }
}
