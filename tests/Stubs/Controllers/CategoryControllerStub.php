<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use tests\Stubs\Models\CategoryStub;

class CategoryControllerStub extends BasicCrudController
{

    protected function model()
    {
        return CategoryStub::class;
    }
}
