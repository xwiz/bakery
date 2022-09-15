<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\QuizOption;

class QuizOptionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_quiz_option()
    {
        $quizOption = QuizOption::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/quiz-options', $quizOption
        );

        $this->assertApiResponse($quizOption);
    }

    /**
     * @test
     */
    public function test_read_quiz_option()
    {
        $quizOption = QuizOption::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-options/'.$quizOption->id
        );

        $this->assertApiResponse($quizOption->toArray());
    }

    /**
     * @test
     */
    public function test_update_quiz_option()
    {
        $quizOption = QuizOption::factory()->create();
        $editedQuizOption = QuizOption::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/quiz-options/'.$quizOption->id,
            $editedQuizOption
        );

        $this->assertApiResponse($editedQuizOption);
    }

    /**
     * @test
     */
    public function test_delete_quiz_option()
    {
        $quizOption = QuizOption::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/quiz-options/'.$quizOption->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-options/'.$quizOption->id
        );

        $this->response->assertStatus(404);
    }
}
