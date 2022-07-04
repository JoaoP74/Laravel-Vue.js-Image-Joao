<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Tests\TestCase;

class FontTest extends TestCase
{
    public function testGetSize(): void
    {
        $font = new Font();
        $font->size(12);
        $this->assertEquals(9, $font->getSize());
    }

    public function testGetGdFont(): void
    {
        $font = new Font();
        $this->assertEquals(1, $font->getGdFont());
        $font->filename(12);
        $this->assertEquals(12, $font->getGdFont());
    }

    public function testCapHeight(): void
    {
        $font = new Font();
        $this->assertEquals(8, $font->capHeight());
    }
}
