<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Rank;

class RankApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_rank()
    {
        $rank = Rank::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/ranks', $rank
        );

        $this->assertApiResponse($rank);
    }

    /**
     * @test
     */
    public function test_read_rank()
    {
        $rank = Rank::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/ranks/'.$rank->id
        );

        $this->assertApiResponse($rank->toArray());
    }

    /**
     * @test
     */
    public function test_update_rank()
    {
        $rank = Rank::factory()->create();
        $editedRank = Rank::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/ranks/'.$rank->id,
            $editedRank
        );

        $this->assertApiResponse($editedRank);
    }

    /**
     * @test
     */
    public function test_delete_rank()
    {
        $rank = Rank::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/ranks/'.$rank->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/ranks/'.$rank->id
        );

        $this->response->assertStatus(404);
    }
}
