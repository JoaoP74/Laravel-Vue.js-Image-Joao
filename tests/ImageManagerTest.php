<?php

namespace Intervention\Image\Tests;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @covers \Intervention\Image\ImageManager
 */
class ImageManagerTest extends TestCase
{
    public function testConstructor()
    {
        $manager = new ImageManager('foo');
        $this->assertInstanceOf(ImageManager::class, $manager);
    }

    public function testCreateGd()
    {
        $manager = new ImageManager('gd');
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testMakeGd()
    {
        $manager = new ImageManager('gd');
        $image = $manager->make(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testCreateImagick()
    {
        $manager = new ImageManager('imagick');
        $image = $manager->create(5, 4);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }

    public function testMakeImagick()
    {
        $manager = new ImageManager('imagick');
        $image = $manager->make(__DIR__ . '/images/red.gif');
        $this->assertInstanceOf(ImageInterface::class, $image);
    }
}
