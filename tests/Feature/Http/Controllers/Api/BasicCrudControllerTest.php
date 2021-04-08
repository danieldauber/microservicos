<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\Traits\TestValidation;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    public function testIndex()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_descritpion']);
        $controller = new CategoryControllerStub();

        $result = $controller->index()->toArray();

        $this->assertEquals([$category->toArray()], $result);
    }
}
