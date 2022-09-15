<?php

namespace Tests\Repositories;

use App\Models\Challenge;
use App\Repositories\ChallengeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ChallengeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ChallengeRepository $challengeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->challengeRepo = app(ChallengeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_challenge()
    {
        $challenge = Challenge::factory()->make()->toArray();

        $createdChallenge = $this->challengeRepo->create($challenge);

        $createdChallenge = $createdChallenge->toArray();
        $this->assertArrayHasKey('id', $createdChallenge);
        $this->assertNotNull($createdChallenge['id'], 'Created Challenge must have id specified');
        $this->assertNotNull(Challenge::find($createdChallenge['id']), 'Challenge with given id must be in DB');
        $this->assertModelData($challenge, $createdChallenge);
    }

    /**
     * @test read
     */
    public function test_read_challenge()
    {
        $challenge = Challenge::factory()->create();

        $dbChallenge = $this->challengeRepo->find($challenge->id);

        $dbChallenge = $dbChallenge->toArray();
        $this->assertModelData($challenge->toArray(), $dbChallenge);
    }

    /**
     * @test update
     */
    public function test_update_challenge()
    {
        $challenge = Challenge::factory()->create();
        $fakeChallenge = Challenge::factory()->make()->toArray();

        $updatedChallenge = $this->challengeRepo->update($fakeChallenge, $challenge->id);

        $this->assertModelData($fakeChallenge, $updatedChallenge->toArray());
        $dbChallenge = $this->challengeRepo->find($challenge->id);
        $this->assertModelData($fakeChallenge, $dbChallenge->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_challenge()
    {
        $challenge = Challenge::factory()->create();

        $resp = $this->challengeRepo->delete($challenge->id);

        $this->assertTrue($resp);
        $this->assertNull(Challenge::find($challenge->id), 'Challenge should not exist in DB');
    }
}
