<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Traits\TestValidation;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidation;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {

        $category = factory(Category::class)->create();

        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }

    public function testShow()
    {

        $category = factory(Category::class)->create();

        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testeInvalidationData()
    {
        $response = $this->json('POST', route('categories.store'), []);

        $this->assetInvalidationFields($response, ['name'], 'required', []);

        $response = $this->json('POST', route('categories.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);

        $this->assetInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
        $this->assetInvalidationFields($response, ['is_active'], 'boolean', []);
    }

    public function testStore()
    {

        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
        ]);

        $category = Category::find($response->json('id'));
        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());

        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false
        ]);

        $response->assertJsonFragment([
            'description' => 'description',
            'is_active' => false
        ]);
    }

    public function testUpdate()
    {

        $category = factory(Category::class)->create([
            'is_active' => false,
            'description' => 'description'
        ]);

        $response = $this->json(
            'PUT',
            route('categories.update', ['category' => $category->id]),
            [
                'name' => 'test',
                'is_active' => true,
                'description' => 'teste'
            ]
        );

        $category = Category::find($response->json('id'));
        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => 'teste',
                'is_active' => true,
                'name' => 'test'
            ]);
    }

    public function testDelete()
    {

        $category = factory(Category::class)->create();


        $response = $this->json(
            'DELETE',
            route('categories.destroy', ['category' => $category->id]),
            []
        );

        $response
            ->assertStatus(204);
    }
}
