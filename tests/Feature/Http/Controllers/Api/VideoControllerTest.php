<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class VideoControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {

        $video = factory(Video::class)->create();

        $response = $this->get(route('videos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$video->toArray()]);
    }

    public function testShow()
    {

        $video = factory(Video::class)->create();

        $response = $this->get(route('videos.show', ['video' => $video->id]));

        $response
            ->assertStatus(200)
            ->assertJson($video->toArray());
    }

    public function testeInvalidationData()
    {
        $response = $this->json('POST', route('videos.store'), []);

        $this->assertInvalidationFields($response, ['name'], 'required', []);

        $response->assertJsonMissingValidationErrors(['is_active']);
        //     ->assertStatus(422)
        //     ->assertJsonValidationErrors(['name'])
        //
        //     ->assertJsonFragment([
        //         \Lang::get('validation.required', ['attribute' => 'name'])
        //     ]);

        // $response = $this->json('POST', route('videos.store'), [
        //     'name' => str_repeat('a', 256),
        //     'is_active' => 'a'
        // ]);


        // $response
        //     ->assertStatus(422)
        //     ->assertJsonValidationErrors(['name', 'is_active'])
        //     ->assertJsonFragment([
        //         \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
        //     ])
        //     ->assertJsonFragment([
        //         \Lang::get('validation.boolean', ['attribute' => 'is active'])
        //     ]);

        // $video = factory(Video::class)->create();

        // $response = $this->json(
        //     'PUT',
        //     route('videos.update', ['video' => $video->id]),
        //     [
        //         'name' => str_repeat('a', 256),
        //         'is_active' => 'a'
        //     ]
        // );

        // $response
        //     ->assertStatus(422)
        //     ->assertJsonValidationErrors(['name', 'is_active'])
        //     ->assertJsonFragment([
        //         \Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
        //     ])
        //     ->assertJsonFragment([
        //         \Lang::get('validation.boolean', ['attribute' => 'is active'])
        //     ]);
    }

    public function testStore()
    {

        $response = $this->json('POST', route('videos.store'), [
            'name' => 'test',
        ]);

        $video = video::find($response->json('id'));
        $response
            ->assertStatus(201)
            ->assertJson($video->toArray());

        $this->assertTrue($response->json('is_active'));

        $response = $this->json('POST', route('videos.store'), [
            'name' => 'test',
            'is_active' => false
        ]);

        $response->assertJsonFragment([
            'is_active' => false
        ]);
    }

    public function testUpdate()
    {

        $video = factory(Video::class)->create([
            'is_active' => false,
        ]);

        $response = $this->json(
            'PUT',
            route('videos.update', ['video' => $video->id]),
            [
                'name' => 'test',
                'is_active' => true,
            ]
        );

        $video = video::find($response->json('id'));
        $response
            ->assertStatus(200)
            ->assertJson($video->toArray())
            ->assertJsonFragment([
                'is_active' => true,
                'name' => 'test'
            ]);
    }


    public function testDelete()
    {

        $video = factory(Video::class)->create();


        $response = $this->json(
            'DELETE',
            route('videos.destroy', ['video' => $video->id]),
            []
        );

        $response
            ->assertStatus(204);
    }
}
