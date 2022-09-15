<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class UserApiTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user()
    {
        $user = User::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/users', $user
        );
        unset($user['password']);

        $this->assertApiResponse($user);
    }

    /**
     * @test
     */
    public function test_read_user()
    {
        $this->withoutMiddleware(\PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class);
        $user = User::factory()->create();
        unset($user->password);

        $this->response = $this->json(
            'GET',
            '/api/v1/users/'.$user->id
        );

        $this->assertApiResponse($user->toArray());
    }

    /**
     * @test
     */
    public function test_update_user()
    {
        $user = User::factory()->create();
        $editedUser = User::factory()->make()->toArray();
        unset($editedUser['email']);
        unset($editedUser['password']);
        $auth = resolve(JWTAuth::class);
        $token = $auth->fromUser($user);

        $this->response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json(
            'PUT',
            '/api/v1/users/'.$user->id,
            $editedUser
        );
        $this->assertApiResponse($editedUser);
    }

    /**
     * @test
     */
    public function test_delete_user()
    {
        $user = User::factory()->create();

        $auth = resolve(JWTAuth::class);
        $token = $auth->fromUser($user);
        $this->response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->json(
            'DELETE',
            '/api/v1/users/'.$user->id
        );

        $this->assertApiSuccess();

        $this->withoutMiddleware(\PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class);

        $this->response = $this->json(
            'GET',
            '/api/v1/users/'.$user->id
        );

        $this->response->assertStatus(404);
    }
}
