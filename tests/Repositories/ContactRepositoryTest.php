<?php

namespace Tests\Repositories;

use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContactRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ContactRepository $contactRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contactRepo = app(ContactRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contact()
    {
        $contact = Contact::factory()->make()->toArray();

        $createdContact = $this->contactRepo->create($contact);

        $createdContact = $createdContact->toArray();
        $this->assertArrayHasKey('id', $createdContact);
        $this->assertNotNull($createdContact['id'], 'Created Contact must have id specified');
        $this->assertNotNull(Contact::find($createdContact['id']), 'Contact with given id must be in DB');
        $this->assertModelData($contact, $createdContact);
    }

    /**
     * @test read
     */
    public function test_read_contact()
    {
        $contact = Contact::factory()->create();

        $dbContact = $this->contactRepo->find($contact->id);

        $dbContact = $dbContact->toArray();
        $this->assertModelData($contact->toArray(), $dbContact);
    }

    /**
     * @test update
     */
    public function test_update_contact()
    {
        $contact = Contact::factory()->create();
        $fakeContact = Contact::factory()->make()->toArray();

        $updatedContact = $this->contactRepo->update($fakeContact, $contact->id);

        $this->assertModelData($fakeContact, $updatedContact->toArray());
        $dbContact = $this->contactRepo->find($contact->id);
        $this->assertModelData($fakeContact, $dbContact->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contact()
    {
        $contact = Contact::factory()->create();

        $resp = $this->contactRepo->delete($contact->id);

        $this->assertTrue($resp);
        $this->assertNull(Contact::find($contact->id), 'Contact should not exist in DB');
    }
}
