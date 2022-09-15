<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Interest;

class InterestApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_interest()
    {
        $interest = Interest::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/interests', $interest
        );

        $this->assertApiResponse($interest);
    }

    /**
     * @test
     */
    public function test_read_interest()
    {
        $interest = Interest::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/interests/'.$interest->id
        );

        $this->assertApiResponse($interest->toArray());
    }

    /**
     * @test
     */
    public function test_update_interest()
    {
        $interest = Interest::factory()->create();
        $editedInterest = Interest::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/interests/'.$interest->id,
            $editedInterest
        );

        $this->assertApiResponse($editedInterest);
    }

    /**
     * @test
     */
    public function test_delete_interest()
    {
        $interest = Interest::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/interests/'.$interest->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/interests/'.$interest->id
        );

        $this->response->assertStatus(404);
    }
}
