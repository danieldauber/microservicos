<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Controllers\Api\CategoryController;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\Traits\TestValidation;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

class BasicCrudControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();

        $this->controller = new CategoryControllerStub();
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

        $result = $this->controller->index()->toArray();

        $this->assertEquals([$category->toArray()], $result);
    }

    /**
     * @expectedException
     *
     * @return void
     */
    public function testInvalidationDataInStore()
    {
        $request = Mockery::mock(Request::class);

        $this->expectException(ValidationException::class);

        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['name' => '']);

        $this->controller->store($request);
    }

    public function testStore()
    {

        $request = Mockery::mock(Request::class);

        $this->expectException(ValidationException::class);

        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['name' => '', 'description' => 'teste_description']);

        $obj = $this->controller->store($request);

        $this->assertEquals(
            CategoryStub::find(1)->toArray(),
            $obj->toArray()
        );
    }

    public function testIfFindOrFailFetchModel()
    {

        $reflectionClass = new ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_descritpion']);

        $result = $reflectionMethod->invokeArgs($this->controller, [$category->id]);

        $this->assertInstanceOf(CategoryStub::class, $result);
    }

    public function testIfFindOrFailException()
    {

        $this->expectException(ModelNotFoundException::class);

        $reflectionClass = new ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);


        $result = $reflectionMethod->invokeArgs($this->controller, [0]);

        $this->assertInstanceOf(CategoryStub::class, $result);
    }

    public function testShow()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_descritpion']);

        $result = $this->controller->show($category->id);

        $this->assertEquals($result->toArray(), CategoryStub::find(1)->toArray());
    }

    public function testUpdate()
    {
        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_descritpion']);

        $request = Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['name' => 'changed', 'description' => 'changed']);

        $result = $this->controller->update($request, $category->id);

        $this->assertEquals($result->toArray(), CategoryStub::find(1)->toArray());
    }

    public function testDestroy()
    {

        /** @var CategoryStub $category */
        $category = CategoryStub::create(['name' => 'test_name', 'description' => 'test_descritpion']);
        $response = $this->controller->destroy($category->id);
        $this->createTestResponse($response)->assertStatus(204);

        $this->assertCount(0, CategoryStub::all());
    }
}
