<?php

namespace Tests\Repositories;

use App\Models\Report;
use App\Repositories\ReportRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ReportRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ReportRepository $reportRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->reportRepo = app(ReportRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_report()
    {
        $report = Report::factory()->make()->toArray();

        $createdReport = $this->reportRepo->create($report);

        $createdReport = $createdReport->toArray();
        $this->assertArrayHasKey('id', $createdReport);
        $this->assertNotNull($createdReport['id'], 'Created Report must have id specified');
        $this->assertNotNull(Report::find($createdReport['id']), 'Report with given id must be in DB');
        $this->assertModelData($report, $createdReport);
    }

    /**
     * @test read
     */
    public function test_read_report()
    {
        $report = Report::factory()->create();

        $dbReport = $this->reportRepo->find($report->id);

        $dbReport = $dbReport->toArray();
        $this->assertModelData($report->toArray(), $dbReport);
    }

    /**
     * @test update
     */
    public function test_update_report()
    {
        $report = Report::factory()->create();
        $fakeReport = Report::factory()->make()->toArray();

        $updatedReport = $this->reportRepo->update($fakeReport, $report->id);

        $this->assertModelData($fakeReport, $updatedReport->toArray());
        $dbReport = $this->reportRepo->find($report->id);
        $this->assertModelData($fakeReport, $dbReport->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_report()
    {
        $report = Report::factory()->create();

        $resp = $this->reportRepo->delete($report->id);

        $this->assertTrue($resp);
        $this->assertNull(Report::find($report->id), 'Report should not exist in DB');
    }
}
