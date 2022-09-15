<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Country;

class CountryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_read_country()
    {
        $country = Country::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/countries/'.$country->id
        );

        $this->assertApiResponse($country->toArray());
    }
}
