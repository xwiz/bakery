<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UserSetting;

class UserSettingApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_setting()
    {
        $userSetting = UserSetting::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/user-settings', $userSetting
        );

        $this->assertApiResponse($userSetting);
    }

    /**
     * @test
     */
    public function test_read_user_setting()
    {
        $userSetting = UserSetting::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/user-settings/'.$userSetting->id
        );

        $this->assertApiResponse($userSetting->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_setting()
    {
        $userSetting = UserSetting::factory()->create();
        $editedUserSetting = UserSetting::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/user-settings/'.$userSetting->id,
            $editedUserSetting
        );

        $this->assertApiResponse($editedUserSetting);
    }

    /**
     * @test
     */
    public function test_delete_user_setting()
    {
        $userSetting = UserSetting::factory()->create();

        $this->response = $this->json(
            'DELETE',
            '/api/v1/user-settings/'.$userSetting->id
        );
        
        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/user-settings/'.$userSetting->id
        );

        $this->response->assertStatus(404);
    }
}
