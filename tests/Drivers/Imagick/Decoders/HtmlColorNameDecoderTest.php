<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Drivers\Imagick\Decoders\HtmlColorNameDecoder;
use Intervention\Image\Tests\TestCase;

class HtmlColorNameDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = new HtmlColorNameDecoder();
        $color = $decoder->decode('tomato');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals('ff6347', $color->toHex());
    }
}
