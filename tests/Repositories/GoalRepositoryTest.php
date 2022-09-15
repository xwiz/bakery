<?php

namespace Tests\Repositories;

use App\Models\Goal;
use App\Repositories\GoalRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class GoalRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected GoalRepository $goalRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->goalRepo = app(GoalRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_goal()
    {
        $goal = Goal::factory()->make()->toArray();

        $createdGoal = $this->goalRepo->create($goal);

        $createdGoal = $createdGoal->toArray();
        $this->assertArrayHasKey('id', $createdGoal);
        $this->assertNotNull($createdGoal['id'], 'Created Goal must have id specified');
        $this->assertNotNull(Goal::find($createdGoal['id']), 'Goal with given id must be in DB');
        $this->assertModelData($goal, $createdGoal);
    }

    /**
     * @test read
     */
    public function test_read_goal()
    {
        $goal = Goal::factory()->create();

        $dbGoal = $this->goalRepo->find($goal->id);

        $dbGoal = $dbGoal->toArray();
        $this->assertModelData($goal->toArray(), $dbGoal);
    }

    /**
     * @test update
     */
    public function test_update_goal()
    {
        $goal = Goal::factory()->create();
        $fakeGoal = Goal::factory()->make()->toArray();

        $updatedGoal = $this->goalRepo->update($fakeGoal, $goal->id);

        $this->assertModelData($fakeGoal, $updatedGoal->toArray());
        $dbGoal = $this->goalRepo->find($goal->id);
        $this->assertModelData($fakeGoal, $dbGoal->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_goal()
    {
        $goal = Goal::factory()->create();

        $resp = $this->goalRepo->delete($goal->id);

        $this->assertTrue($resp);
        $this->assertNull(Goal::find($goal->id), 'Goal should not exist in DB');
    }
}
