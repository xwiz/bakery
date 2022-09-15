<?php

namespace Tests\Repositories;

use App\Models\QuizAnswer;
use App\Repositories\QuizAnswerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class QuizAnswerRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected QuizAnswerRepository $quizAnswerRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->quizAnswerRepo = app(QuizAnswerRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->make()->toArray();

        $createdQuizAnswer = $this->quizAnswerRepo->create($quizAnswer);

        $createdQuizAnswer = $createdQuizAnswer->toArray();
        $this->assertArrayHasKey('id', $createdQuizAnswer);
        $this->assertNotNull($createdQuizAnswer['id'], 'Created QuizAnswer must have id specified');
        $this->assertNotNull(QuizAnswer::find($createdQuizAnswer['id']), 'QuizAnswer with given id must be in DB');
        $this->assertModelData($quizAnswer, $createdQuizAnswer);
    }

    /**
     * @test read
     */
    public function test_read_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->create();

        $dbQuizAnswer = $this->quizAnswerRepo->find($quizAnswer->id);

        $dbQuizAnswer = $dbQuizAnswer->toArray();
        $this->assertModelData($quizAnswer->toArray(), $dbQuizAnswer);
    }

    /**
     * @test update
     */
    public function test_update_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->create();
        $fakeQuizAnswer = QuizAnswer::factory()->make()->toArray();

        $updatedQuizAnswer = $this->quizAnswerRepo->update($fakeQuizAnswer, $quizAnswer->id);

        $this->assertModelData($fakeQuizAnswer, $updatedQuizAnswer->toArray());
        $dbQuizAnswer = $this->quizAnswerRepo->find($quizAnswer->id);
        $this->assertModelData($fakeQuizAnswer, $dbQuizAnswer->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_quiz_answer()
    {
        $quizAnswer = QuizAnswer::factory()->create();

        $resp = $this->quizAnswerRepo->delete($quizAnswer->id);

        $this->assertTrue($resp);
        $this->assertNull(QuizAnswer::find($quizAnswer->id), 'QuizAnswer should not exist in DB');
    }
}
