<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{

    protected abstract function model();

    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean'
    ];

    public function index()
    {
        return $this->model()::all();
    }

    // public function store(Request $request)
    // {
    // }

    // public function show(Category $category)
    // {
    // }

    // public function update(Request $request, Category $category)
    // {
    // }

    // public function destroy(Category $category)
    // {
    // }
}
