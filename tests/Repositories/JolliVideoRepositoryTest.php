<?php

namespace Tests\Repositories;

use App\Models\JolliVideo;
use App\Repositories\JolliVideoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class JolliVideoRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected JolliVideoRepository $jolliVideoRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->jolliVideoRepo = app(JolliVideoRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->make()->toArray();

        $createdJolliVideo = $this->jolliVideoRepo->create($jolliVideo);

        $createdJolliVideo = $createdJolliVideo->toArray();
        $this->assertArrayHasKey('id', $createdJolliVideo);
        $this->assertNotNull($createdJolliVideo['id'], 'Created JolliVideo must have id specified');
        $this->assertNotNull(JolliVideo::find($createdJolliVideo['id']), 'JolliVideo with given id must be in DB');
        $this->assertModelData($jolliVideo, $createdJolliVideo);
    }

    /**
     * @test read
     */
    public function test_read_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->create();

        $dbJolliVideo = $this->jolliVideoRepo->find($jolliVideo->id);

        $dbJolliVideo = $dbJolliVideo->toArray();
        $this->assertModelData($jolliVideo->toArray(), $dbJolliVideo);
    }

    /**
     * @test update
     */
    public function test_update_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->create();
        $fakeJolliVideo = JolliVideo::factory()->make()->toArray();

        $updatedJolliVideo = $this->jolliVideoRepo->update($fakeJolliVideo, $jolliVideo->id);

        $this->assertModelData($fakeJolliVideo, $updatedJolliVideo->toArray());
        $dbJolliVideo = $this->jolliVideoRepo->find($jolliVideo->id);
        $this->assertModelData($fakeJolliVideo, $dbJolliVideo->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_jolli_video()
    {
        $jolliVideo = JolliVideo::factory()->create();

        $resp = $this->jolliVideoRepo->delete($jolliVideo->id);

        $this->assertTrue($resp);
        $this->assertNull(JolliVideo::find($jolliVideo->id), 'JolliVideo should not exist in DB');
    }
}
