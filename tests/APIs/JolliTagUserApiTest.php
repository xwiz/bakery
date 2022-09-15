<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\JolliTagUser;

class JolliTagUserApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/jolli-tag-users', $jolliTagUser
        );

        $this->assertApiResponse($jolliTagUser);
    }

    /**
     * @test
     */
    public function test_read_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-tag-users/'.$jolliTagUser->id
        );

        $this->assertApiResponse($jolliTagUser->toArray());
    }

    /**
     * @test
     */
    public function test_update_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->create();
        $editedJolliTagUser = JolliTagUser::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/jolli-tag-users/'.$jolliTagUser->id,
            $editedJolliTagUser
        );

        $this->assertApiResponse($editedJolliTagUser);
    }

    /**
     * @test
     */
    public function test_delete_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/jolli-tag-users/'.$jolliTagUser->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/jolli-tag-users/'.$jolliTagUser->id
        );

        $this->response->assertStatus(404);
    }
}
