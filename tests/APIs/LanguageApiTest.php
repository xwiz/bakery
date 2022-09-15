<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Language;

class LanguageApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_language()
    {
        $language = Language::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/languages', $language
        );

        $this->assertApiResponse($language);
    }

    /**
     * @test
     */
    public function test_read_language()
    {
        $language = Language::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/languages/'.$language->id
        );

        $this->assertApiResponse($language->toArray());
    }

    /**
     * @test
     */
    public function test_update_language()
    {
        $language = Language::factory()->create();
        $editedLanguage = Language::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/languages/'.$language->id,
            $editedLanguage
        );

        $this->assertApiResponse($editedLanguage);
    }

    /**
     * @test
     */
    public function test_delete_language()
    {
        $language = Language::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/languages/'.$language->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/languages/'.$language->id
        );

        $this->response->assertStatus(404);
    }
}
