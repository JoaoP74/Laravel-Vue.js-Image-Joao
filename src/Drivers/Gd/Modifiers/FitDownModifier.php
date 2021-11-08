<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResizeGeometrically;

class FitDownModifier extends FitModifier implements ModifierInterface
{
    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return $this->resizeGeometrically($this->getCropSize($image))
                ->toWidth($this->target->getWidth())
                ->toHeight($this->target->getHeight())
                ->scaleDown();
    }
}
