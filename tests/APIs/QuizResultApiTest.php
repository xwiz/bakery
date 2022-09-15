<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\QuizResult;

class QuizResultApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_quiz_result()
    {
        $quizResult = QuizResult::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/quiz-results', $quizResult
        );

        $this->assertApiResponse($quizResult);
    }

    /**
     * @test
     */
    public function test_read_quiz_result()
    {
        $quizResult = QuizResult::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-results/'.$quizResult->id
        );

        $this->assertApiResponse($quizResult->toArray());
    }

    /**
     * @test
     */
    public function test_update_quiz_result()
    {
        $quizResult = QuizResult::factory()->create();
        $editedQuizResult = QuizResult::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/quiz-results/'.$quizResult->id,
            $editedQuizResult
        );

        $this->assertApiResponse($editedQuizResult);
    }

    /**
     * @test
     */
    public function test_delete_quiz_result()
    {
        $quizResult = QuizResult::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/quiz-results/'.$quizResult->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-results/'.$quizResult->id
        );

        $this->response->assertStatus(404);
    }
}
