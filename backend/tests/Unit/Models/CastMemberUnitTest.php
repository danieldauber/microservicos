<?php

namespace Tests\Unit;

use App\Models\CastMember;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class CastMemberUnitTest extends TestCase
{

    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'type'];
        $this->assertEquals($fillable, $this->castMember->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class, Uuid::class,
        ];

        $classTraits = array_keys(class_uses(CastMember::class));
        $this->assertEqualsCanonicalizing($traits, $classTraits);
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->castMember->getDates());
        };
        $this->assertCount(count($dates), $this->castMember->getDates());
    }

    public function testCasts()
    {
        $casts = ['id' => 'string', 'type' => 'integer'];

        $castMember = new CastMember();

        $this->assertEquals($casts, $castMember->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->castMember->incrementing);
    }
}
