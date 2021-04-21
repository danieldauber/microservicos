<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\TestValidations;
use Tests\TestCase;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;
    protected $video;
    private $sendData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory(Video::class)->create();

        $this->sendData = [
            'title' => 'title',
            'description' => 'description',
            'year_launched' => 1999,
            'rating' => Video::RATING_LIST[0],
            'duration' => 100
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('videos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.show', ['video' => $this->video->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->video->toArray());
    }

    public function testeInvalidationDataRequired()
    {
        $data = [
            'title' => '',
            'description' => '',
            'year_launched' => '',
            'rating' => '',
            'duration' => ''
        ];
        $this->assertInvalidationInStore($data, 'required');
        $this->assertInvalidationInUpdate($data, 'required');
    }

    public function testeInvalidationDataMax()
    {
        $data = [
            'title' => str_repeat('a', 256)
        ];
        $this->assertInvalidationInStore($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdate($data, 'max.string', ['max' => 255]);
    }

    public function testeInvalidationDataInteger()
    {
        $data = [
            'duration' => 's'
        ];
        $this->assertInvalidationInStore($data, 'integer',);
        $this->assertInvalidationInUpdate($data, 'integer',);
    }

    public function testeInvalidationYear()
    {
        $data = [
            'year_launched' => 's'
        ];
        $this->assertInvalidationInStore($data, 'date_format', ['format' => 'Y']);
        $this->assertInvalidationInUpdate($data, 'date_format', ['format' => 'Y']);
    }

    public function testeInvalidationRating()
    {
        $data = [
            'rating' => 0
        ];
        $this->assertInvalidationInStore($data, 'in');
        $this->assertInvalidationInUpdate($data, 'in');
    }

    public function testSave()
    {

        $datas = [
            [
                'send_data' => $this->sendData,
                'test_data' => $this->sendData + ['opened' => false],
            ],
            [
                'send_data' => $this->sendData + ['opened' => true],
                'test_data' => $this->sendData + ['opened' => true],
            ],
            [
                'send_data' => $this->sendData + ['rating' => Video::RATING_LIST[1]],
                'test_data' => $this->sendData + ['rating' => Video::RATING_LIST[1]],
            ],
        ];

        foreach ($datas as $data) {
            $response = $this->assertStore($data['send_data'], $data['test_data'] + ['deleted_at' => null]);

            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);

            $response = $this->assertUpdate($data['send_data'], $data['test_data'] + ['deleted_at' => null]);
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);
        }
    }

    public function testDelete()
    {
        $response = $this->json(
            'DELETE',
            route('videos.destroy', ['video' => $this->video->id]),
            []
        );
        $response->assertStatus(204);

        $this->assertNull(Video::find($this->video->id));
        $this->assertNotNull(Video::withTrashed()->find($this->video->id));
    }

    protected function routeStore()
    {
        return route('videos.store');
    }

    protected function routeUpdate()
    {
        return route('videos.update', ['video' => $this->video->id]);
    }

    protected function model()
    {
        return Video::class;
    }
}
