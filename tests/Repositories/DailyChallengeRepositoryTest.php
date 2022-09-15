<?php

namespace Tests\Repositories;

use App\Models\DailyChallenge;
use App\Repositories\DailyChallengeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DailyChallengeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected DailyChallengeRepository $dailyChallengeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dailyChallengeRepo = app(DailyChallengeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->make()->toArray();

        $createdDailyChallenge = $this->dailyChallengeRepo->create($dailyChallenge);

        $createdDailyChallenge = $createdDailyChallenge->toArray();
        $this->assertArrayHasKey('id', $createdDailyChallenge);
        $this->assertNotNull($createdDailyChallenge['id'], 'Created DailyChallenge must have id specified');
        $this->assertNotNull(DailyChallenge::find($createdDailyChallenge['id']), 'DailyChallenge with given id must be in DB');
        $this->assertModelData($dailyChallenge, $createdDailyChallenge);
    }

    /**
     * @test read
     */
    public function test_read_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->create();

        $dbDailyChallenge = $this->dailyChallengeRepo->find($dailyChallenge->id);

        $dbDailyChallenge = $dbDailyChallenge->toArray();
        $this->assertModelData($dailyChallenge->toArray(), $dbDailyChallenge);
    }

    /**
     * @test update
     */
    public function test_update_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->create();
        $fakeDailyChallenge = DailyChallenge::factory()->make()->toArray();

        $updatedDailyChallenge = $this->dailyChallengeRepo->update($fakeDailyChallenge, $dailyChallenge->id);

        $this->assertModelData($fakeDailyChallenge, $updatedDailyChallenge->toArray());
        $dbDailyChallenge = $this->dailyChallengeRepo->find($dailyChallenge->id);
        $this->assertModelData($fakeDailyChallenge, $dbDailyChallenge->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_daily_challenge()
    {
        $dailyChallenge = DailyChallenge::factory()->create();

        $resp = $this->dailyChallengeRepo->delete($dailyChallenge->id);

        $this->assertTrue($resp);
        $this->assertNull(DailyChallenge::find($dailyChallenge->id), 'DailyChallenge should not exist in DB');
    }
}
