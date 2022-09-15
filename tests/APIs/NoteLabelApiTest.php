<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\NoteLabel;

class NoteLabelApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_note_label()
    {
        $noteLabel = NoteLabel::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/note-labels', $noteLabel
        );

        $this->assertApiResponse($noteLabel);
    }

    /**
     * @test
     */
    public function test_read_note_label()
    {
        $noteLabel = NoteLabel::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/note-labels/'.$noteLabel->id
        );

        $this->assertApiResponse($noteLabel->toArray());
    }

    /**
     * @test
     */
    public function test_update_note_label()
    {
        $noteLabel = NoteLabel::factory()->create();
        $editedNoteLabel = NoteLabel::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/note-labels/'.$noteLabel->id,
            $editedNoteLabel
        );

        $this->assertApiResponse($editedNoteLabel);
    }

    /**
     * @test
     */
    public function test_delete_note_label()
    {
        $noteLabel = NoteLabel::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/note-labels/'.$noteLabel->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/note-labels/'.$noteLabel->id
        );

        $this->response->assertStatus(404);
    }
}
