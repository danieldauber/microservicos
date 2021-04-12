<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Gender;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenderControllerTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {

        $gender = factory(Gender::class)->create();

        $response = $this->get(route('genders.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$gender->toArray()]);
    }

    public function testShow()
    {

        $gender = factory(Gender::class)->create();

        $response = $this->get(route('genders.show', ['gender' => $gender->id]));

        $response
            ->assertStatus(200)
            ->assertJson($gender->toArray());
    }

    public function testeInvalidationData()
    {
        $response = $this->json('POST', route('genders.store'), []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]);

        $response = $this->json('POST', route('genders.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);


        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);

        $gender = factory(Gender::class)->create();

        $response = $this->json(
            'PUT',
            route('genders.update', ['gender' => $gender->id]),
            [
                'name' => str_repeat('a', 256),
                'is_active' => 'a'
            ]
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }

    public function testStore()
    {

        $response = $this->json('POST', route('genders.store'), [
            'name' => 'test',
        ]);

        $gender = gender::find($response->json('id'));
        $response
            ->assertStatus(201)
            ->assertJson($gender->toArray());

        $this->assertTrue($response->json('is_active'));

        $response = $this->json('POST', route('genders.store'), [
            'name' => 'test',
            'is_active' => false
        ]);

        $response->assertJsonFragment([
            'is_active' => false
        ]);
    }

    public function testUpdate()
    {

        $gender = factory(Gender::class)->create([
            'is_active' => false,
        ]);

        $response = $this->json(
            'PUT',
            route('genders.update', ['gender' => $gender->id]),
            [
                'name' => 'test',
                'is_active' => true,
            ]
        );

        $gender = gender::find($response->json('id'));
        $response
            ->assertStatus(200)
            ->assertJson($gender->toArray())
            ->assertJsonFragment([
                'is_active' => true,
                'name' => 'test'
            ]);
    }


    public function testDelete()
    {

        $gender = factory(gender::class)->create();


        $response = $this->json(
            'DELETE',
            route('genders.destroy', ['gender' => $gender->id]),
            []
        );

        $response
            ->assertStatus(204);
    }
}
