<?php

namespace Tests\Repositories;

use App\Models\Personality;
use App\Repositories\PersonalityRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PersonalityRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected PersonalityRepository $personalityRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->personalityRepo = app(PersonalityRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_personality()
    {
        $personality = Personality::factory()->make()->toArray();

        $createdPersonality = $this->personalityRepo->create($personality);

        $createdPersonality = $createdPersonality->toArray();
        $this->assertArrayHasKey('id', $createdPersonality);
        $this->assertNotNull($createdPersonality['id'], 'Created Personality must have id specified');
        $this->assertNotNull(Personality::find($createdPersonality['id']), 'Personality with given id must be in DB');
        $this->assertModelData($personality, $createdPersonality);
    }

    /**
     * @test read
     */
    public function test_read_personality()
    {
        $personality = Personality::factory()->create();

        $dbPersonality = $this->personalityRepo->find($personality->id);

        $dbPersonality = $dbPersonality->toArray();
        $this->assertModelData($personality->toArray(), $dbPersonality);
    }

    /**
     * @test update
     */
    public function test_update_personality()
    {
        $personality = Personality::factory()->create();
        $fakePersonality = Personality::factory()->make()->toArray();

        $updatedPersonality = $this->personalityRepo->update($fakePersonality, $personality->id);

        $this->assertModelData($fakePersonality, $updatedPersonality->toArray());
        $dbPersonality = $this->personalityRepo->find($personality->id);
        $this->assertModelData($fakePersonality, $dbPersonality->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_personality()
    {
        $personality = Personality::factory()->create();

        $resp = $this->personalityRepo->delete($personality->id);

        $this->assertTrue($resp);
        $this->assertNull(Personality::find($personality->id), 'Personality should not exist in DB');
    }
}
