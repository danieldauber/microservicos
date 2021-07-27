<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CastMemberTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {

        factory(CastMember::class, 1)->create();

        $castMembers = CastMember::all();
        $castMemberKeys = array_keys($castMembers->first()->getAttributes());
        $this->assertCount(1, $castMembers);
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'type',
            'created_at', 'updated_at', 'deleted_at'
        ], $castMemberKeys);
    }

    public function testCreate()
    {

        $castMember = CastMember::create([
            'name' => 'teste',
            'type' => 1

        ]);
        $castMember->refresh();

        $this->assertTrue((preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i',  $castMember->id) == 1));

        $this->assertEquals('teste', $castMember->name);
        $this->assertEquals(1, $castMember->type);
    }

    public function testEdit()
    {

        /** @var CastMember $castMember */
        $castMember = factory(CastMember::class)->create([
            'name' => 'test name',
            'type' => CastMember::TYPE_ACTOR
        ])->first();

        $castMember->update([
            'name' => 'teste_name',
            'type' => CastMember::TYPE_DIRECTOR
        ]);

        $data = [
            'name' => 'teste_name',
            'type' => CastMember::TYPE_DIRECTOR
        ];

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $castMember->{$key});
        }
    }

    public function testDelete()
    {
        /** @var CastMember $castMember */
        $castMember = CastMember::create([
            'name' => 'teste',
            'type' => CastMember::TYPE_ACTOR
        ]);
        $castMember->refresh();

        $castMember->delete();

        $castMember = CastMember::all();

        $this->assertCount(0, $castMember);
    }
}
