<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class PixelateModifier implements ModifierInterface
{
    public function __construct(protected int $size)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->pixelateFrame($frame);
        }

        return $image;
    }

    protected function pixelateFrame(Frame $frame): void
    {
        $size = $frame->getSize();

        $frame->getCore()->scaleImage(
            max(1, ($size->width() / $this->size)),
            max(1, ($size->height() / $this->size))
        );

        $frame->getCore()->scaleImage($size->width(), $size->height());
    }
}
