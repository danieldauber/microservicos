<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{

    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Genre::class, 1)->create();

        $Genre = Genre::all();
        $GenreKeys = array_keys($Genre->first()->getAttributes());
        $this->assertCount(1, $Genre);
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'is_active',
            'created_at', 'updated_at', 'deleted_at'
        ], $GenreKeys);
    }

    public function testCreate()
    {

        $Genre = Genre::create([
            'name' => 'teste',

        ]);
        $Genre->refresh();

        $this->assertTrue((preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i',  $Genre->id) == 1));


        $this->assertEquals('teste', $Genre->name);
        $this->assertTrue($Genre->is_active);
    }

    public function testEdit()
    {
        /** @var Genre $Genre */
        $Genre = factory(Genre::class)->create([
            'is_active' => false
        ])->first();

        $Genre->update([
            'name' => 'teste_name',
            'is_active' => true
        ]);

        $data = [
            'name' => 'teste_name',
            'is_active' => true
        ];

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $Genre->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Genre $Genre */
        $Genre = Genre::create([
            'name' => 'teste',

        ]);
        $Genre->refresh();

        $Genre->delete();

        $Genre = Genre::all();

        $this->assertCount(0, $Genre);
    }
}
