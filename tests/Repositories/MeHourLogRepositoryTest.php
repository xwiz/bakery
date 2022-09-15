<?php

namespace Tests\Repositories;

use App\Models\MeHourLog;
use App\Repositories\MeHourLogRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MeHourLogRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected MeHourLogRepository $meHourLogRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->meHourLogRepo = app(MeHourLogRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->make()->toArray();

        $createdMeHourLog = $this->meHourLogRepo->create($meHourLog);

        $createdMeHourLog = $createdMeHourLog->toArray();
        $this->assertArrayHasKey('id', $createdMeHourLog);
        $this->assertNotNull($createdMeHourLog['id'], 'Created MeHourLog must have id specified');
        $this->assertNotNull(MeHourLog::find($createdMeHourLog['id']), 'MeHourLog with given id must be in DB');
        $this->assertModelData($meHourLog, $createdMeHourLog);
    }

    /**
     * @test read
     */
    public function test_read_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->create();

        $dbMeHourLog = $this->meHourLogRepo->find($meHourLog->id);

        $dbMeHourLog = $dbMeHourLog->toArray();
        $this->assertModelData($meHourLog->toArray(), $dbMeHourLog);
    }

    /**
     * @test update
     */
    public function test_update_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->create();
        $fakeMeHourLog = MeHourLog::factory()->make()->toArray();

        $updatedMeHourLog = $this->meHourLogRepo->update($fakeMeHourLog, $meHourLog->id);

        $this->assertModelData($fakeMeHourLog, $updatedMeHourLog->toArray());
        $dbMeHourLog = $this->meHourLogRepo->find($meHourLog->id);
        $this->assertModelData($fakeMeHourLog, $dbMeHourLog->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_me_hour_log()
    {
        $meHourLog = MeHourLog::factory()->create();

        $resp = $this->meHourLogRepo->delete($meHourLog->id);

        $this->assertTrue($resp);
        $this->assertNull(MeHourLog::find($meHourLog->id), 'MeHourLog should not exist in DB');
    }
}
