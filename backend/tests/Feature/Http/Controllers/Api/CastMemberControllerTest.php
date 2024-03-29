<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Resources\CastMemberResource;
use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\TestCase;
use Tests\Traits\TestResources;
use Tests\Traits\TestSaves;

class CastMemberControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves, TestResources;
    protected $castMember;
    private $fieldsSerialized = [
        'id',
        'name',
        'type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = factory(CastMember::class)->create([
            'type' => CastMember::TYPE_DIRECTOR
        ]);
    }

    public function testIndex()
    {
        $response = $this->get(route('cast_members.index'));

        $response
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => $this->fieldsSerialized
                    ],
                    'meta' => [],
                    'links' => []
                ]
            )
            ->assertJsonFragment($this->castMember->toArray());
    }

    public function testShow()
    {
        $response = $this->json('GET', route('cast_members.show', ['cast_member' => $this->castMember->id]));
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->fieldsSerialized
            ])
            ->assertJsonFragment($this->castMember->toArray());

        $this->assertResource($response, new CastMemberResource($this->castMember));
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
            [
                'name' => 'test',
                'type' => CastMember::TYPE_DIRECTOR
            ],
            [
                'name' => 'test',
                'type' => CastMember::TYPE_ACTOR
            ]
        ];
        foreach ($data as $key => $value) {
            $response = $this->assertStore($value, $value + ['deleted_at' => null]);
            $response->assertJsonStructure([
                'data' => $this->fieldsSerialized
            ]);
            $this->assertResource($response, new CastMemberResource(
                CastMember::find($response->json('data.id'))
            ));
        }
    }

    public function testUpdate()
    {

        $data = [
            'name' => 'test',
            'type' => CastMember::TYPE_ACTOR
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'data' => $this->fieldsSerialized
        ]);
        $this->assertResource($response, new CastMemberResource(
            CastMember::find($response->json('data.id'))
        ));
    }

    public function testDelete()
    {
        $response = $this->json('DELETE', route('cast_members.destroy', [
            'cast_member' => $this->castMember->id
        ]));
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
