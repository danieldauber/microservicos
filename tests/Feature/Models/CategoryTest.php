<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {

        factory(Category::class, 1)->create();

        $categories = Category::all();
        $categoryKeys = array_keys($categories->first()->getAttributes());
        $this->assertCount(1, $categories);
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'description', 'is_active',
            'created_at', 'updated_at', 'deleted_at'
        ], $categoryKeys);
    }

    public function testCreate()
    {

        $category = Category::create([
            'name' => 'teste',

        ]);
        $category->refresh();

        $this->assertTrue((preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i',  $category->id) == 1));

        $this->assertEquals('teste', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
    }

    public function testEdit()
    {

        /** @var Category $category */
        $category = factory(Category::class)->create([
            'description' => 'test description',
            'is_active' => false
        ])->first();

        $category->update([
            'name' => 'teste_name',
            'description' => 'teste descriçao',
            'is_active' => true
        ]);

        $data = [
            'name' => 'teste_name',
            'description' => 'teste descriçao',
            'is_active' => true
        ];

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Category $category */
        $category = Category::create([
            'name' => 'teste',

        ]);
        $category->refresh();

        $category->delete();

        $category = Category::all();

        $this->assertCount(0, $category);
    }
}
