<?php

namespace Tests\Repositories;

use App\Models\NoteLabel;
use App\Repositories\NoteLabelRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class NoteLabelRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected NoteLabelRepository $noteLabelRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->noteLabelRepo = app(NoteLabelRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_note_label()
    {
        $noteLabel = NoteLabel::factory()->make()->toArray();

        $createdNoteLabel = $this->noteLabelRepo->create($noteLabel);

        $createdNoteLabel = $createdNoteLabel->toArray();
        $this->assertArrayHasKey('id', $createdNoteLabel);
        $this->assertNotNull($createdNoteLabel['id'], 'Created NoteLabel must have id specified');
        $this->assertNotNull(NoteLabel::find($createdNoteLabel['id']), 'NoteLabel with given id must be in DB');
        $this->assertModelData($noteLabel, $createdNoteLabel);
    }

    /**
     * @test read
     */
    public function test_read_note_label()
    {
        $noteLabel = NoteLabel::factory()->create();

        $dbNoteLabel = $this->noteLabelRepo->find($noteLabel->id);

        $dbNoteLabel = $dbNoteLabel->toArray();
        $this->assertModelData($noteLabel->toArray(), $dbNoteLabel);
    }

    /**
     * @test update
     */
    public function test_update_note_label()
    {
        $noteLabel = NoteLabel::factory()->create();
        $fakeNoteLabel = NoteLabel::factory()->make()->toArray();

        $updatedNoteLabel = $this->noteLabelRepo->update($fakeNoteLabel, $noteLabel->id);

        $this->assertModelData($fakeNoteLabel, $updatedNoteLabel->toArray());
        $dbNoteLabel = $this->noteLabelRepo->find($noteLabel->id);
        $this->assertModelData($fakeNoteLabel, $dbNoteLabel->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_note_label()
    {
        $noteLabel = NoteLabel::factory()->create();

        $resp = $this->noteLabelRepo->delete($noteLabel->id);

        $this->assertTrue($resp);
        $this->assertNull(NoteLabel::find($noteLabel->id), 'NoteLabel should not exist in DB');
    }
}
