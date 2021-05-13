<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreController extends BasicCrudController
{

    private $rules;

    public function __construct()
    {
        $this->rules = [
            'name' => 'required|max:255',
            'is_active' => 'boolean',
            'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL'
        ];
    }

    public function store(Request $request)
    {
        $validate = $this->validate($request, $this->rulesStore());

        $self = $this;
        $obj = DB::transaction(function () use ($validate, $request, $self) {
            $obj = $this->model()::create($validate);
            $self->handleRelations($obj, $request);
            return $obj;
        });

        $obj->refresh();
        return $obj;
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $validate = $this->validate($request, $this->rulesUpdate());
        $self = $this;
        $obj = DB::transaction(function () use ($validate, $request, $self, $obj) {
            $obj->update($validate);
            $self->handleRelations($obj, $request);
            return $obj;
        });

        $obj->refresh();

        return $obj;
    }

    protected function handleRelations($genre, Request $request)
    {
        $genre->categories()->sync($request->get('categories_id'));
    }

    protected function model()
    {
        return Genre::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }
}
