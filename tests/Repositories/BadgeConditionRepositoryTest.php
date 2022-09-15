<?php

namespace Tests\Repositories;

use App\Models\BadgeCondition;
use App\Repositories\BadgeConditionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BadgeConditionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected BadgeConditionRepository $badgeConditionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->badgeConditionRepo = app(BadgeConditionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->make()->toArray();

        $createdBadgeCondition = $this->badgeConditionRepo->create($badgeCondition);

        $createdBadgeCondition = $createdBadgeCondition->toArray();
        $this->assertArrayHasKey('id', $createdBadgeCondition);
        $this->assertNotNull($createdBadgeCondition['id'], 'Created BadgeCondition must have id specified');
        $this->assertNotNull(BadgeCondition::find($createdBadgeCondition['id']), 'BadgeCondition with given id must be in DB');
        $this->assertModelData($badgeCondition, $createdBadgeCondition);
    }

    /**
     * @test read
     */
    public function test_read_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->create();

        $dbBadgeCondition = $this->badgeConditionRepo->find($badgeCondition->id);

        $dbBadgeCondition = $dbBadgeCondition->toArray();
        $this->assertModelData($badgeCondition->toArray(), $dbBadgeCondition);
    }

    /**
     * @test update
     */
    public function test_update_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->create();
        $fakeBadgeCondition = BadgeCondition::factory()->make()->toArray();

        $updatedBadgeCondition = $this->badgeConditionRepo->update($fakeBadgeCondition, $badgeCondition->id);

        $this->assertModelData($fakeBadgeCondition, $updatedBadgeCondition->toArray());
        $dbBadgeCondition = $this->badgeConditionRepo->find($badgeCondition->id);
        $this->assertModelData($fakeBadgeCondition, $dbBadgeCondition->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_badge_condition()
    {
        $badgeCondition = BadgeCondition::factory()->create();

        $resp = $this->badgeConditionRepo->delete($badgeCondition->id);

        $this->assertTrue($resp);
        $this->assertNull(BadgeCondition::find($badgeCondition->id), 'BadgeCondition should not exist in DB');
    }
}
