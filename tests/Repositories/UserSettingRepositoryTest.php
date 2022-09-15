<?php

namespace Tests\Repositories;

use App\Models\UserSetting;
use App\Repositories\UserSettingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserSettingRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected UserSettingRepository $userSettingRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userSettingRepo = app(UserSettingRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_setting()
    {
        $userSetting = UserSetting::factory()->make()->toArray();

        $createdUserSetting = $this->userSettingRepo->create($userSetting);

        $createdUserSetting = $createdUserSetting->toArray();
        $this->assertArrayHasKey('id', $createdUserSetting);
        $this->assertNotNull($createdUserSetting['id'], 'Created UserSetting must have id specified');
        $this->assertNotNull(UserSetting::find($createdUserSetting['id']), 'UserSetting with given id must be in DB');
        $this->assertModelData($userSetting, $createdUserSetting);
    }

    /**
     * @test read
     */
    public function test_read_user_setting()
    {
        $userSetting = UserSetting::factory()->create();

        $dbUserSetting = $this->userSettingRepo->find($userSetting->id);

        $dbUserSetting = $dbUserSetting->toArray();
        $this->assertModelData($userSetting->toArray(), $dbUserSetting);
    }

    /**
     * @test update
     */
    public function test_update_user_setting()
    {
        $userSetting = UserSetting::factory()->create();
        $fakeUserSetting = UserSetting::factory()->make()->toArray();

        $updatedUserSetting = $this->userSettingRepo->update($fakeUserSetting, $userSetting->id);

        $this->assertModelData($fakeUserSetting, $updatedUserSetting->toArray());
        $dbUserSetting = $this->userSettingRepo->find($userSetting->id);
        $this->assertModelData($fakeUserSetting, $dbUserSetting->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_setting()
    {
        $userSetting = UserSetting::factory()->create();

        $resp = $this->userSettingRepo->delete($userSetting->id);

        $this->assertTrue($resp);
        $this->assertNull(UserSetting::find($userSetting->id), 'UserSetting should not exist in DB');
    }
}
