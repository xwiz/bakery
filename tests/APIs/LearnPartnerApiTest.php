<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\LearnPartner;

class LearnPartnerApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/learn-partners', $learnPartner
        );

        $this->assertApiResponse($learnPartner);
    }

    /**
     * @test
     */
    public function test_read_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/learn-partners/'.$learnPartner->id
        );

        $this->assertApiResponse($learnPartner->toArray());
    }

    /**
     * @test
     */
    public function test_update_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->create();
        $editedLearnPartner = LearnPartner::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/learn-partners/'.$learnPartner->id,
            $editedLearnPartner
        );

        $this->assertApiResponse($editedLearnPartner);
    }

    /**
     * @test
     */
    public function test_delete_learn_partner()
    {
        $learnPartner = LearnPartner::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/learn-partners/'.$learnPartner->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/learn-partners/'.$learnPartner->id
        );

        $this->response->assertStatus(404);
    }
}
