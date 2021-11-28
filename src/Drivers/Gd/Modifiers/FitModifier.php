<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanHandleInput;
use Intervention\Image\Traits\CanResizeGeometrically;

class FitModifier implements ModifierInterface
{
    protected $crop;
    protected $resize;

    public function __construct(SizeInterface $crop, SizeInterface $resize)
    {
        $this->crop = $crop;
        $this->resize = $resize;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->modify($frame);
        }

        return $image;
    }

    protected function modify(FrameInterface $frame): void
    {
        // create new image
        $modified = imagecreatetruecolor(
            $this->resize->getWidth(),
            $this->resize->getHeight()
        );

        // get current image
        $current = $frame->getCore();

        // preserve transparency
        $transIndex = imagecolortransparent($current);

        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);
        } else {
            imagealphablending($modified, false);
            imagesavealpha($modified, true);
        }

        // copy content from resource
        imagecopyresampled(
            $modified,
            $current,
            0,
            0,
            $this->crop->getPivot()->getX(),
            $this->crop->getPivot()->getY(),
            $this->resize->getWidth(),
            $this->resize->getHeight(),
            $this->crop->getWidth(),
            $this->crop->getHeight()
        );

        imagedestroy($current);

        // set new content as recource
        $frame->setCore($modified);
    }
}
