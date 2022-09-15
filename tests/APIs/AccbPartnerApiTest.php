<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\AccbPartner;

class AccbPartnerApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/accb-partners', $accbPartner
        );

        $this->assertApiResponse($accbPartner);
    }

    /**
     * @test
     */
    public function test_read_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/accb-partners/'.$accbPartner->id
        );

        $this->assertApiResponse($accbPartner->toArray());
    }

    /**
     * @test
     */
    public function test_update_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->create();
        $editedAccbPartner = AccbPartner::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/accb-partners/'.$accbPartner->id,
            $editedAccbPartner
        );

        $this->assertApiResponse($editedAccbPartner);
    }

    /**
     * @test
     */
    public function test_delete_accb_partner()
    {
        $accbPartner = AccbPartner::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/accb-partners/'.$accbPartner->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/accb-partners/'.$accbPartner->id
        );

        $this->response->assertStatus(404);
    }
}
