<?php

namespace Intervention\Image\Tests\Geometry;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Geometry\{
    Size,
    Point,
};

class SizeTest extends TestCase
{
    public function testConstructor()
    {
        $pivot = new Point(10, 20);
        $size = new Size(300, 200, $pivot);
        $this->assertInstanceOf(Size::class, $size);
        $this->assertInstanceOf(Point::class, $size->getPivot());
        $this->assertEquals(300, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
    }

    public function testGetWidth()
    {
        $size = new Size(800, 600);
        $this->assertEquals(800, $size->getWidth());
    }

    public function testGetHeight()
    {
        $size = new Size(800, 600);
        $this->assertEquals(600, $size->getHeight());
    }

    public function testGetAspectRatio()
    {
        $size = new Size(800, 600);
        $this->assertEquals(1.33333333333, $size->getAspectRatio());

        $size = new Size(100, 100);
        $this->assertEquals(1, $size->getAspectRatio());

        $size = new Size(1920, 1080);
        $this->assertEquals(1.777777777778, $size->getAspectRatio());
    }

    public function testFitsInto()
    {
        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(100, 100));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(1000, 100));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(100, 1000));
        $this->assertFalse($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(800, 600));
        $this->assertTrue($fits);

        $box = new Size(800, 600);
        $fits = $box->fitsInto(new Size(1000, 1000));
        $this->assertTrue($fits);

        $box = new Size(100, 100);
        $fits = $box->fitsInto(new Size(800, 600));
        $this->assertTrue($fits);

        $box = new Size(100, 100);
        $fits = $box->fitsInto(new Size(80, 60));
        $this->assertFalse($fits);
    }

    public function testIsLandscape()
    {
        $box = new Size(100, 100);
        $this->assertFalse($box->isLandscape());

        $box = new Size(100, 200);
        $this->assertFalse($box->isLandscape());

        $box = new Size(300, 200);
        $this->assertTrue($box->isLandscape());
    }

    public function testIsPortrait()
    {
        $box = new Size(100, 100);
        $this->assertFalse($box->isPortrait());

        $box = new Size(200, 100);
        $this->assertFalse($box->isPortrait());

        $box = new Size(200, 300);
        $this->assertTrue($box->isPortrait());
    }
}
