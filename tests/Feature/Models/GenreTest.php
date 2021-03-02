<?php

namespace Tests\Feature\Models;

use App\Models\Gender;
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
        factory(Gender::class, 1)->create();

        $gender = Gender::all();
        $genderKeys = array_keys($gender->first()->getAttributes());
        $this->assertCount(1, $gender);
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'is_active',
            'created_at', 'updated_at', 'deleted_at'
        ], $genderKeys);
    }

    public function testCreate()
    {

        $gender = Gender::create([
            'name' => 'teste',

        ]);
        $gender->refresh();

        $this->assertTrue((preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i',  $gender->id) == 1));


        $this->assertEquals('teste', $gender->name);
        $this->assertTrue($gender->is_active);
    }

    public function testEdit()
    {
        /** @var Gender $gender */
        $gender = factory(Gender::class)->create([
            'is_active' => false
        ])->first();

        $gender->update([
            'name' => 'teste_name',
            'is_active' => true
        ]);

        $data = [
            'name' => 'teste_name',
            'is_active' => true
        ];

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $gender->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Gender $gender */
        $gender = Gender::create([
            'name' => 'teste',

        ]);
        $gender->refresh();

        $gender->delete();

        $gender = Gender::all();

        $this->assertCount(0, $gender);
    }
}
