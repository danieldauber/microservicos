<?php

namespace Tests\Feature\Models;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoTest extends TestCase
{

    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Video::class, 1)->create();

        $video = Video::all();
        $videoKeys = array_keys($video->first()->getAttributes());

        $this->assertCount(1, $video);
        $this->assertEqualsCanonicalizing([
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'deleted_at',
            'created_at',
            'updated_at',
        ], $videoKeys);
    }

    public function testCreate()
    {

        $data = [
            'title' => 'test',
            'description' => 'test',
            'year_launched' => 1999,
            'rating' => 'L',
            'duration' => 180,
        ];

        $video = Video::create($data);
        $video->refresh();

        $this->assertTrue((preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i',  $video->id) == 1));


        foreach ($data as $key => $value) {
            $this->assertEquals($value, $video->{$key});
        }
        // $this->assertEquals('test', $video->title);
    }

    public function testEdit()
    {
        /** @var Video $video */
        $video = factory(Video::class)->create([
            'title' => '2342342',
            'description' => 'sdfsd'
        ])->first();

        $video->update([
            'title' => 'teste_name',
            'description' => 'description'
        ]);

        $data = [
            'title' => 'teste_name',
            'description' => 'description'
        ];

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $video->{$key});
        }
    }

    public function testDelete()
    {

        $data = [
            'title' => 'test',
            'description' => 'test',
            'year_launched' => 1999,
            'rating' => 'L',
            'duration' => 180,
        ];

        /** @var Video $video */
        $video = Video::create($data);
        $video->refresh();

        $video->delete();

        $video = Video::all();

        $this->assertCount(0, $video);
    }
}
