<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;

class FrameTest extends TestCase
{
    protected function getTestFrame(): Frame
    {
        return new Frame(imagecreatetruecolor(3, 2));
    }

    public function testConstructor(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Frame::class, $frame);
    }

    public function testSetGetDelay()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getDelay());

        $result = $frame->setDelay(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getDelay());
    }

    public function testSetGetDispose()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(1, $frame->getDispose());

        $result = $frame->setDispose(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getDispose());
    }

    public function testSetGetOffsetLeft()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getOffsetLeft());

        $result = $frame->setOffsetLeft(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetLeft());
    }

    public function testSetGetOffsetTop()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getOffsetTop());

        $result = $frame->setOffsetTop(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetTop());
    }

    public function testSetGetOffset()
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->getOffsetTop());
        $this->assertEquals(0, $frame->getOffsetLeft());

        $result = $frame->setOffset(100, 200);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->getOffsetLeft());
        $this->assertEquals(200, $frame->getOffsetTop());
    }

    public function testToImage(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Image::class, $frame->toImage());
    }
}
