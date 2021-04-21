<?php

namespace Tests\Unit;

use App\Models\Genre;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class GenreUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testModel()
    {
        $fillable = ['name', 'is_active'];
        $castMember = new Genre();
        $this->assertEquals($fillable, $castMember->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, Uuid::class,
        ];

        $classTraits = array_keys(class_uses(Genre::class));
        $this->assertEqualsCanonicalizing($traits, $classTraits);
    }

    public function testCasts()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];

        $castMember = new Genre();

        $this->assertEquals($casts, $castMember->getCasts());
    }
}
