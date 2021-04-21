<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\TestCase;
use Tests\Traits\TestSaves;

class CastMemberControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;
    protected $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = factory(CastMember::class)->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('cast_members.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('cast_members.show', ['cast_member' => $this->castMember->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->castMember->toArray());
    }

    public function testeInvalidationData()
    {
        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInStore($data, 'required');
        $this->assertInvalidationInUpdate($data, 'required');

        $data = [
            'name' => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStore($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdate($data, 'max.string', ['max' => 255]);
    }

    public function testStore()
    {
        $data = [
            'name' => 'test',
            'type' => 1
        ];

        $response = $this->assertStore($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
    }

    public function testUpdate()
    {

        $this->castMember = factory(CastMember::class)->create([
            'type' => 2
        ]);

        $data = [
            'name' => 'test',
            'type' => 2
        ];

        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
    }

    public function testDelete()
    {
        $response = $this->json(
            'DELETE',
            route('cast_members.destroy', ['cast_member' => $this->castMember->id]),
            []
        );
        $response->assertStatus(204);

        $this->assertNull(CastMember::find($this->castMember->id));
        $this->assertNotNull(CastMember::withTrashed()->find($this->castMember->id));
    }

    protected function routeStore()
    {
        return route('cast_members.store');
    }

    protected function routeUpdate()
    {
        return route('cast_members.update', ['cast_member' => $this->castMember->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}
