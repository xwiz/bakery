<?php

namespace Tests\Repositories;

use App\Models\QuizResult;
use App\Repositories\QuizResultRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class QuizResultRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected QuizResultRepository $quizResultRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->quizResultRepo = app(QuizResultRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_quiz_result()
    {
        $quizResult = QuizResult::factory()->make()->toArray();

        $createdQuizResult = $this->quizResultRepo->create($quizResult);

        $createdQuizResult = $createdQuizResult->toArray();
        $this->assertArrayHasKey('id', $createdQuizResult);
        $this->assertNotNull($createdQuizResult['id'], 'Created QuizResult must have id specified');
        $this->assertNotNull(QuizResult::find($createdQuizResult['id']), 'QuizResult with given id must be in DB');
        $this->assertModelData($quizResult, $createdQuizResult);
    }

    /**
     * @test read
     */
    public function test_read_quiz_result()
    {
        $quizResult = QuizResult::factory()->create();

        $dbQuizResult = $this->quizResultRepo->find($quizResult->id);

        $dbQuizResult = $dbQuizResult->toArray();
        $this->assertModelData($quizResult->toArray(), $dbQuizResult);
    }

    /**
     * @test update
     */
    public function test_update_quiz_result()
    {
        $quizResult = QuizResult::factory()->create();
        $fakeQuizResult = QuizResult::factory()->make()->toArray();

        $updatedQuizResult = $this->quizResultRepo->update($fakeQuizResult, $quizResult->id);

        $this->assertModelData($fakeQuizResult, $updatedQuizResult->toArray());
        $dbQuizResult = $this->quizResultRepo->find($quizResult->id);
        $this->assertModelData($fakeQuizResult, $dbQuizResult->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_quiz_result()
    {
        $quizResult = QuizResult::factory()->create();

        $resp = $this->quizResultRepo->delete($quizResult->id);

        $this->assertTrue($resp);
        $this->assertNull(QuizResult::find($quizResult->id), 'QuizResult should not exist in DB');
    }
}
