<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid as TraitsUuid;

class Genre extends Model
{
    use SoftDeletes, TraitsUuid;

    protected $fillable = ['name', 'is_active'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];
    public $incrementing = false;

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Videos::class);
    }
}
