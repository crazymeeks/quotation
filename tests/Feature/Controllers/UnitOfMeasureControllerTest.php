<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\UnitOfMeasure;

class UnitOfMeasureControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticateAsAdmin();
    }

    /** @dataProvider data */
    public function testShouldAddUnitOfMeasure(array $data)
    {
        $response = $this->json('POST', route('admin.uom.post.save'), $data);

        $this->assertEquals('Unit of measure successfully saved.', $response->original['message']);
        $this->assertDatabaseHas('unit_of_measures', $data);
    }

    /** @dataProvider data */
    public function testShouldUpdateUnitOfMeasure(array $data)
    {
        $uom = UnitOfMeasure::factory()->create([
            'title' => 'kls'
        ]);

        $data['uuid'] = $uom->uuid;
        
        $response = $this->json('POST', route('admin.uom.post.save'), $data);

        $this->assertEquals('Unit of measure successfully saved.', $response->original['message']);
        $this->assertDatabaseHas('unit_of_measures', $data);
    }

    public function testShouldDeleteUnitOfMeasure()
    {
        $uom = UnitOfMeasure::factory()->create();
        $this->json('DELETE', route('admin.uom.delete'), ['uuid' => $uom->uuid]);
        $uom = UnitOfMeasure::first();
        $this->assertNull($uom);
    }

    /** @dataProvider data */
    public function testShouldReAddDeletedUnitOfMeasure(array $data)
    {
        UnitOfMeasure::factory()->create([
            'title' => sprintf("%s.%s", $data['title'], uniqid()),
            'deleted_at' => now()->__toString(),
        ]);

        $response = $this->json('POST', route('admin.uom.post.save'), $data);

        $this->assertEquals('Unit of measure successfully saved.', $response->original['message']);
    }

    public function data()
    {
        $data = [
            'title' => 'pieces'
        ];

        return [
            array($data)
        ];
    }
}