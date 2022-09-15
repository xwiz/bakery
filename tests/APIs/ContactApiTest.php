<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Contact;

class ContactApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contact()
    {
        $contact = Contact::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/contacts', $contact
        );

        $this->assertApiResponse($contact);
    }

    /**
     * @test
     */
    public function test_read_contact()
    {
        $contact = Contact::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/contacts/'.$contact->id
        );

        $this->assertApiResponse($contact->toArray());
    }

    /**
     * @test
     */
    public function test_update_contact()
    {
        $contact = Contact::factory()->create();
        $editedContact = Contact::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/contacts/'.$contact->id,
            $editedContact
        );

        $this->assertApiResponse($editedContact);
    }

    /**
     * @test
     */
    public function test_delete_contact()
    {
        $contact = Contact::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/contacts/'.$contact->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/contacts/'.$contact->id
        );

        $this->response->assertStatus(404);
    }
}
