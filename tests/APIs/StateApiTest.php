<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\State;

class StateApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;


    /**
     * @test
     */
    public function test_read_state()
    {
        $state = State::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/states/'.$state->id
        );

        $this->assertApiResponse($state->toArray());
    }

}
