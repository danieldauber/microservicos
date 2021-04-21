<?php

namespace Tests\Unit;

use App\Models\CastMember;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class CastMemberUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testModel()
    {
        $fillable = ['name', 'type'];
        $castMember = new CastMember();
        $this->assertEquals($fillable, $castMember->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, Uuid::class,
        ];

        $classTraits = array_keys(class_uses(CastMember::class));
        $this->assertEqualsCanonicalizing($traits, $classTraits);
    }

    public function testCasts()
    {
        $casts = ['id' => 'string', 'type' => 'integer'];

        $castMember = new CastMember();

        $this->assertEquals($casts, $castMember->getCasts());
    }
}
