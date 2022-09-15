<?php

namespace Tests\Repositories;

use App\Models\JolliThought;
use App\Repositories\JolliThoughtRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class JolliThoughtRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected JolliThoughtRepository $jolliThoughtRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->jolliThoughtRepo = app(JolliThoughtRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->make()->toArray();

        $createdJolliThought = $this->jolliThoughtRepo->create($jolliThought);

        $createdJolliThought = $createdJolliThought->toArray();
        $this->assertArrayHasKey('id', $createdJolliThought);
        $this->assertNotNull($createdJolliThought['id'], 'Created JolliThought must have id specified');
        $this->assertNotNull(JolliThought::find($createdJolliThought['id']), 'JolliThought with given id must be in DB');
        $this->assertModelData($jolliThought, $createdJolliThought);
    }

    /**
     * @test read
     */
    public function test_read_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->create();

        $dbJolliThought = $this->jolliThoughtRepo->find($jolliThought->id);

        $dbJolliThought = $dbJolliThought->toArray();
        $this->assertModelData($jolliThought->toArray(), $dbJolliThought);
    }

    /**
     * @test update
     */
    public function test_update_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->create();
        $fakeJolliThought = JolliThought::factory()->make()->toArray();

        $updatedJolliThought = $this->jolliThoughtRepo->update($fakeJolliThought, $jolliThought->id);

        $this->assertModelData($fakeJolliThought, $updatedJolliThought->toArray());
        $dbJolliThought = $this->jolliThoughtRepo->find($jolliThought->id);
        $this->assertModelData($fakeJolliThought, $dbJolliThought->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_jolli_thought()
    {
        $jolliThought = JolliThought::factory()->create();

        $resp = $this->jolliThoughtRepo->delete($jolliThought->id);

        $this->assertTrue($resp);
        $this->assertNull(JolliThought::find($jolliThought->id), 'JolliThought should not exist in DB');
    }
}
