<?php

namespace Tests\Repositories;

use App\Models\Badge;
use App\Repositories\BadgeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BadgeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected BadgeRepository $badgeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->badgeRepo = app(BadgeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_badge()
    {
        $badge = Badge::factory()->make()->toArray();

        $createdBadge = $this->badgeRepo->create($badge);

        $createdBadge = $createdBadge->toArray();
        $this->assertArrayHasKey('id', $createdBadge);
        $this->assertNotNull($createdBadge['id'], 'Created Badge must have id specified');
        $this->assertNotNull(Badge::find($createdBadge['id']), 'Badge with given id must be in DB');
        $this->assertModelData($badge, $createdBadge);
    }

    /**
     * @test read
     */
    public function test_read_badge()
    {
        $badge = Badge::factory()->create();

        $dbBadge = $this->badgeRepo->find($badge->id);

        $dbBadge = $dbBadge->toArray();
        $this->assertModelData($badge->toArray(), $dbBadge);
    }

    /**
     * @test update
     */
    public function test_update_badge()
    {
        $badge = Badge::factory()->create();
        $fakeBadge = Badge::factory()->make()->toArray();

        $updatedBadge = $this->badgeRepo->update($fakeBadge, $badge->id);

        $this->assertModelData($fakeBadge, $updatedBadge->toArray());
        $dbBadge = $this->badgeRepo->find($badge->id);
        $this->assertModelData($fakeBadge, $dbBadge->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_badge()
    {
        $badge = Badge::factory()->create();

        $resp = $this->badgeRepo->delete($badge->id);

        $this->assertTrue($resp);
        $this->assertNull(Badge::find($badge->id), 'Badge should not exist in DB');
    }
}
