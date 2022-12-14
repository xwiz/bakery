<?php

namespace Tests\Repositories;

use App\Models\State;
use App\Repositories\StateRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class StateRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected StateRepository $stateRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->stateRepo = app(StateRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_state()
    {
        $state = State::factory()->make()->toArray();

        $createdState = $this->stateRepo->create($state);

        $createdState = $createdState->toArray();
        $this->assertArrayHasKey('id', $createdState);
        $this->assertNotNull($createdState['id'], 'Created State must have id specified');
        $this->assertNotNull(State::find($createdState['id']), 'State with given id must be in DB');
        $this->assertModelData($state, $createdState);
    }

    /**
     * @test read
     */
    public function test_read_state()
    {
        $state = State::factory()->create();

        $dbState = $this->stateRepo->find($state->id);

        $dbState = $dbState->toArray();
        $this->assertModelData($state->toArray(), $dbState);
    }

    /**
     * @test update
     */
    public function test_update_state()
    {
        $state = State::factory()->create();
        $fakeState = State::factory()->make()->toArray();

        $updatedState = $this->stateRepo->update($fakeState, $state->id);

        $this->assertModelData($fakeState, $updatedState->toArray());
        $dbState = $this->stateRepo->find($state->id);
        $this->assertModelData($fakeState, $dbState->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_state()
    {
        $state = State::factory()->create();

        $resp = $this->stateRepo->delete($state->id);

        $this->assertTrue($resp);
        $this->assertNull(State::find($state->id), 'State should not exist in DB');
    }
}
