<?php

namespace Tests\Repositories;

use App\Models\QuizQuestion;
use App\Repositories\QuizQuestionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class QuizQuestionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected QuizQuestionRepository $quizQuestionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->quizQuestionRepo = app(QuizQuestionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->make()->toArray();

        $createdQuizQuestion = $this->quizQuestionRepo->create($quizQuestion);

        $createdQuizQuestion = $createdQuizQuestion->toArray();
        $this->assertArrayHasKey('id', $createdQuizQuestion);
        $this->assertNotNull($createdQuizQuestion['id'], 'Created QuizQuestion must have id specified');
        $this->assertNotNull(QuizQuestion::find($createdQuizQuestion['id']), 'QuizQuestion with given id must be in DB');
        $this->assertModelData($quizQuestion, $createdQuizQuestion);
    }

    /**
     * @test read
     */
    public function test_read_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->create();

        $dbQuizQuestion = $this->quizQuestionRepo->find($quizQuestion->id);

        $dbQuizQuestion = $dbQuizQuestion->toArray();
        $this->assertModelData($quizQuestion->toArray(), $dbQuizQuestion);
    }

    /**
     * @test update
     */
    public function test_update_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->create();
        $fakeQuizQuestion = QuizQuestion::factory()->make()->toArray();

        $updatedQuizQuestion = $this->quizQuestionRepo->update($fakeQuizQuestion, $quizQuestion->id);

        $this->assertModelData($fakeQuizQuestion, $updatedQuizQuestion->toArray());
        $dbQuizQuestion = $this->quizQuestionRepo->find($quizQuestion->id);
        $this->assertModelData($fakeQuizQuestion, $dbQuizQuestion->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_quiz_question()
    {
        $quizQuestion = QuizQuestion::factory()->create();

        $resp = $this->quizQuestionRepo->delete($quizQuestion->id);

        $this->assertTrue($resp);
        $this->assertNull(QuizQuestion::find($quizQuestion->id), 'QuizQuestion should not exist in DB');
    }
}
