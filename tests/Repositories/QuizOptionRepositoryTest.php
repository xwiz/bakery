<?php

namespace Tests\Repositories;

use App\Models\QuizOption;
use App\Repositories\QuizOptionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class QuizOptionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected QuizOptionRepository $quizOptionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->quizOptionRepo = app(QuizOptionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_quiz_option()
    {
        $quizOption = QuizOption::factory()->make()->toArray();

        $createdQuizOption = $this->quizOptionRepo->create($quizOption);

        $createdQuizOption = $createdQuizOption->toArray();
        $this->assertArrayHasKey('id', $createdQuizOption);
        $this->assertNotNull($createdQuizOption['id'], 'Created QuizOption must have id specified');
        $this->assertNotNull(QuizOption::find($createdQuizOption['id']), 'QuizOption with given id must be in DB');
        $this->assertModelData($quizOption, $createdQuizOption);
    }

    /**
     * @test read
     */
    public function test_read_quiz_option()
    {
        $quizOption = QuizOption::factory()->create();

        $dbQuizOption = $this->quizOptionRepo->find($quizOption->id);

        $dbQuizOption = $dbQuizOption->toArray();
        $this->assertModelData($quizOption->toArray(), $dbQuizOption);
    }

    /**
     * @test update
     */
    public function test_update_quiz_option()
    {
        $quizOption = QuizOption::factory()->create();
        $fakeQuizOption = QuizOption::factory()->make()->toArray();

        $updatedQuizOption = $this->quizOptionRepo->update($fakeQuizOption, $quizOption->id);

        $this->assertModelData($fakeQuizOption, $updatedQuizOption->toArray());
        $dbQuizOption = $this->quizOptionRepo->find($quizOption->id);
        $this->assertModelData($fakeQuizOption, $dbQuizOption->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_quiz_option()
    {
        $quizOption = QuizOption::factory()->create();

        $resp = $this->quizOptionRepo->delete($quizOption->id);

        $this->assertTrue($resp);
        $this->assertNull(QuizOption::find($quizOption->id), 'QuizOption should not exist in DB');
    }
}
