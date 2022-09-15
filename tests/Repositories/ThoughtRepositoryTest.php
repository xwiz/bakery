<?php

namespace Tests\Repositories;

use App\Models\Thought;
use App\Repositories\ThoughtRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ThoughtRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ThoughtRepository $thoughtRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->thoughtRepo = app(ThoughtRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_thought()
    {
        $thought = Thought::factory()->make()->toArray();

        $createdThought = $this->thoughtRepo->create($thought);

        $createdThought = $createdThought->toArray();
        $this->assertArrayHasKey('id', $createdThought);
        $this->assertNotNull($createdThought['id'], 'Created Thought must have id specified');
        $this->assertNotNull(Thought::find($createdThought['id']), 'Thought with given id must be in DB');
        $this->assertModelData($thought, $createdThought);
    }

    /**
     * @test read
     */
    public function test_read_thought()
    {
        $thought = Thought::factory()->create();

        $dbThought = $this->thoughtRepo->find($thought->id);

        $dbThought = $dbThought->toArray();
        $this->assertModelData($thought->toArray(), $dbThought);
    }

    /**
     * @test update
     */
    public function test_update_thought()
    {
        $thought = Thought::factory()->create();
        $fakeThought = Thought::factory()->make()->toArray();

        $updatedThought = $this->thoughtRepo->update($fakeThought, $thought->id);

        $this->assertModelData($fakeThought, $updatedThought->toArray());
        $dbThought = $this->thoughtRepo->find($thought->id);
        $this->assertModelData($fakeThought, $dbThought->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_thought()
    {
        $thought = Thought::factory()->create();

        $resp = $this->thoughtRepo->delete($thought->id);

        $this->assertTrue($resp);
        $this->assertNull(Thought::find($thought->id), 'Thought should not exist in DB');
    }
}
