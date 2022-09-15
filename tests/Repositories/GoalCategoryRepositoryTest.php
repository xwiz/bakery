<?php

namespace Tests\Repositories;

use App\Models\GoalCategory;
use App\Repositories\GoalCategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class GoalCategoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected GoalCategoryRepository $goalCategoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->goalCategoryRepo = app(GoalCategoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_goal_category()
    {
        $goalCategory = GoalCategory::factory()->make()->toArray();

        $createdGoalCategory = $this->goalCategoryRepo->create($goalCategory);

        $createdGoalCategory = $createdGoalCategory->toArray();
        $this->assertArrayHasKey('id', $createdGoalCategory);
        $this->assertNotNull($createdGoalCategory['id'], 'Created GoalCategory must have id specified');
        $this->assertNotNull(GoalCategory::find($createdGoalCategory['id']), 'GoalCategory with given id must be in DB');
        $this->assertModelData($goalCategory, $createdGoalCategory);
    }

    /**
     * @test read
     */
    public function test_read_goal_category()
    {
        $goalCategory = GoalCategory::factory()->create();

        $dbGoalCategory = $this->goalCategoryRepo->find($goalCategory->id);

        $dbGoalCategory = $dbGoalCategory->toArray();
        $this->assertModelData($goalCategory->toArray(), $dbGoalCategory);
    }

    /**
     * @test update
     */
    public function test_update_goal_category()
    {
        $goalCategory = GoalCategory::factory()->create();
        $fakeGoalCategory = GoalCategory::factory()->make()->toArray();

        $updatedGoalCategory = $this->goalCategoryRepo->update($fakeGoalCategory, $goalCategory->id);

        $this->assertModelData($fakeGoalCategory, $updatedGoalCategory->toArray());
        $dbGoalCategory = $this->goalCategoryRepo->find($goalCategory->id);
        $this->assertModelData($fakeGoalCategory, $dbGoalCategory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_goal_category()
    {
        $goalCategory = GoalCategory::factory()->create();

        $resp = $this->goalCategoryRepo->delete($goalCategory->id);

        $this->assertTrue($resp);
        $this->assertNull(GoalCategory::find($goalCategory->id), 'GoalCategory should not exist in DB');
    }
}
