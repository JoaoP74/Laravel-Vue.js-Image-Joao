<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Modifiers\DrawLineModifier;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class DrawLineModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $line = new Line(new Point(0, 0), new Point(10, 0), 4);
        $line->background('b53517');
        $image->modify(new DrawLineModifier(new Point(0, 0), $line));
        $this->assertEquals('b53517', $image->pickColor(0, 0)->toHex());
    }
}
