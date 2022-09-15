<?php

namespace Tests\Repositories;

use App\Models\Reaction;
use App\Repositories\ReactionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ReactionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ReactionRepository $reactionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->reactionRepo = app(ReactionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_reaction()
    {
        $reaction = Reaction::factory()->make()->toArray();

        $createdReaction = $this->reactionRepo->create($reaction);

        $createdReaction = $createdReaction->toArray();
        $this->assertArrayHasKey('id', $createdReaction);
        $this->assertNotNull($createdReaction['id'], 'Created Reaction must have id specified');
        $this->assertNotNull(Reaction::find($createdReaction['id']), 'Reaction with given id must be in DB');
        $this->assertModelData($reaction, $createdReaction);
    }

    /**
     * @test read
     */
    public function test_read_reaction()
    {
        $reaction = Reaction::factory()->create();

        $dbReaction = $this->reactionRepo->find($reaction->id);

        $dbReaction = $dbReaction->toArray();
        $this->assertModelData($reaction->toArray(), $dbReaction);
    }

    /**
     * @test update
     */
    public function test_update_reaction()
    {
        $reaction = Reaction::factory()->create();
        $fakeReaction = Reaction::factory()->make()->toArray();

        $updatedReaction = $this->reactionRepo->update($fakeReaction, $reaction->id);

        $this->assertModelData($fakeReaction, $updatedReaction->toArray());
        $dbReaction = $this->reactionRepo->find($reaction->id);
        $this->assertModelData($fakeReaction, $dbReaction->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_reaction()
    {
        $reaction = Reaction::factory()->create();

        $resp = $this->reactionRepo->delete($reaction->id);

        $this->assertTrue($resp);
        $this->assertNull(Reaction::find($reaction->id), 'Reaction should not exist in DB');
    }
}
