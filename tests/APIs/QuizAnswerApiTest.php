<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\QuizAnswer;

class QuizAnswerApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/quiz-answers', $quizAnswer
        );

        $this->assertApiResponse($quizAnswer);
    }

    /**
     * @test
     */
    public function test_read_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-answers/'.$quizAnswer->id
        );

        $this->assertApiResponse($quizAnswer->toArray());
    }

    /**
     * @test
     */
    public function test_update_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->create();
        $editedQuizAnswer = QuizAnswer::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/quiz-answers/'.$quizAnswer->id,
            $editedQuizAnswer
        );

        $this->assertApiResponse($editedQuizAnswer);
    }

    /**
     * @test
     */
    public function test_delete_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/quiz-answers/'.$quizAnswer->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/quiz-answers/'.$quizAnswer->id
        );

        $this->response->assertStatus(404);
    }
}
