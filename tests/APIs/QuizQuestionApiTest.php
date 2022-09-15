<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\QuizQuestion;

class QuizQuestionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/quiz-questions', $quizQuestion
        );

        $this->assertApiResponse($quizQuestion);
    }

    /**
     * @test
     */
    public function test_read_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-questions/'.$quizQuestion->id
        );

        $this->assertApiResponse($quizQuestion->toArray());
    }

    /**
     * @test
     */
    public function test_update_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->create();
        $editedQuizQuestion = QuizQuestion::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/quiz-questions/'.$quizQuestion->id,
            $editedQuizQuestion
        );

        $this->assertApiResponse($editedQuizQuestion);
    }

    /**
     * @test
     */
    public function test_delete_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/quiz-questions/'.$quizQuestion->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-questions/'.$quizQuestion->id
        );

        $this->response->assertStatus(404);
    }
}
