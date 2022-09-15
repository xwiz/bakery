<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UserReferral;

class UserReferralApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_referral()
    {
        $userReferral = UserReferral::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/user-referrals', $userReferral
        );

        $this->assertApiResponse($userReferral);
    }

    /**
     * @test
     */
    public function test_read_user_referral()
    {
        $userReferral = UserReferral::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/user-referrals/'.$userReferral->id
        );

        $this->assertApiResponse($userReferral->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_referral()
    {
        $userReferral = UserReferral::factory()->create();
        $editedUserReferral = UserReferral::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/user-referrals/'.$userReferral->id,
            $editedUserReferral
        );

        $this->assertApiResponse($editedUserReferral);
    }

    /**
     * @test
     */
    public function test_delete_user_referral()
    {
        $userReferral = UserReferral::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/user-referrals/'.$userReferral->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/user-referrals/'.$userReferral->id
        );

        $this->response->assertStatus(404);
    }
}
