<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\Modifiers\InvertModifier;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

class InvertModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->createTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('ffa601', $image->pickColor(25, 25)->toHex());
        $image->modify(new InvertModifier());
        $this->assertEquals('ff510f', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('0059fe', $image->pickColor(25, 25)->toHex());
    }
}
