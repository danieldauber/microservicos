<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use tests\Stubs\Models\CategoryStub;
use Tests\Stubs\Resources\CategoryStubResource;

class CategoryControllerStub extends BasicCrudController
{

    private $rules = [
        'name' => 'required|max:255',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected function model()
    {
        return CategoryStub::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }

    protected function resource()
    {
        return CategoryStubResource::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }
}
