<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidation;
use Tests\TestCase;

class CastMemberControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidation;
    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = factory(CastMember::class)->create([
            'type' => CastMember::TYPE_DIRECTOR
        ]);
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
        $response = $this->json('POST', route('cast_members.store'), []);

        $this->assetInvalidationFields($response, ['name'], 'required', []);

        $response = $this->json('POST', route('cast_members.store'), [
            'name' => str_repeat('a', 256),
            'type' => 's'
        ]);

        $this->assetInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
        $this->assetInvalidationFields($response, ['type'], 'in', []);
    }

    public function testStore()
    {

        $response = $this->json('POST', route('cast_members.store'), [
            'name' => 'test',
            'type' => CastMember::TYPE_DIRECTOR
        ]);

        $castMember = CastMember::find($response->json('id'));
        $response
            ->assertStatus(201)
            ->assertJson($castMember->toArray());
    }

    public function testUpdate()
    {

        $response = $this->json(
            'PUT',
            route('cast_members.update', ['cast_member' => $this->castMember->id]),
            [
                'name' => 'test',
                'type' => CastMember::TYPE_ACTOR
            ]
        );

        $castMember = CastMember::find($response->json('id'));
        $response
            ->assertStatus(200)
            ->assertJson($castMember->toArray())
            ->assertJsonFragment([
                'name' => 'test',
                'type' => CastMember::TYPE_ACTOR
            ]);
    }

    public function testDelete()
    {

        $response = $this->json(
            'DELETE',
            route('cast_members.destroy', ['cast_member' => $this->castMember->id]),
            []
        );

        $response
            ->assertStatus(204);
    }
}
