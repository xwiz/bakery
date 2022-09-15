<?php

namespace Tests\Repositories;

use App\Models\JolliTagUser;
use App\Repositories\JolliTagUserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class JolliTagUserRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected JolliTagUserRepository $jolliTagUserRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->jolliTagUserRepo = app(JolliTagUserRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->make()->toArray();

        $createdJolliTagUser = $this->jolliTagUserRepo->create($jolliTagUser);

        $createdJolliTagUser = $createdJolliTagUser->toArray();
        $this->assertArrayHasKey('id', $createdJolliTagUser);
        $this->assertNotNull($createdJolliTagUser['id'], 'Created JolliTagUser must have id specified');
        $this->assertNotNull(JolliTagUser::find($createdJolliTagUser['id']), 'JolliTagUser with given id must be in DB');
        $this->assertModelData($jolliTagUser, $createdJolliTagUser);
    }

    /**
     * @test read
     */
    public function test_read_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->create();

        $dbJolliTagUser = $this->jolliTagUserRepo->find($jolliTagUser->id);

        $dbJolliTagUser = $dbJolliTagUser->toArray();
        $this->assertModelData($jolliTagUser->toArray(), $dbJolliTagUser);
    }

    /**
     * @test update
     */
    public function test_update_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->create();
        $fakeJolliTagUser = JolliTagUser::factory()->make()->toArray();

        $updatedJolliTagUser = $this->jolliTagUserRepo->update($fakeJolliTagUser, $jolliTagUser->id);

        $this->assertModelData($fakeJolliTagUser, $updatedJolliTagUser->toArray());
        $dbJolliTagUser = $this->jolliTagUserRepo->find($jolliTagUser->id);
        $this->assertModelData($fakeJolliTagUser, $dbJolliTagUser->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_jolli_tag_user()
    {
        $jolliTagUser = JolliTagUser::factory()->create();

        $resp = $this->jolliTagUserRepo->delete($jolliTagUser->id);

        $this->assertTrue($resp);
        $this->assertNull(JolliTagUser::find($jolliTagUser->id), 'JolliTagUser should not exist in DB');
    }
}
