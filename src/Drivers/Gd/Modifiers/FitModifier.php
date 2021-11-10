<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

class FitModifier extends CropResizeModifier implements ModifierInterface
{
    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        $imagesize = $image->getSize();

        // auto height
        $size = $this->resizeGeometrically($this->target)
                ->toWidth($imagesize->getWidth())
                ->scale();

        if (!$size->fitsInto($imagesize)) {
            // auto width
            $size = $this->resizeGeometrically($this->target)
                ->toHeight($imagesize->getHeight())
                ->scale();
        }

        return $size->alignPivotTo(
            $imagesize->alignPivot($this->position),
            $this->position
        );
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return $this->resizeGeometrically($this->getCropSize($image))
                ->toWidth($this->target->getWidth())
                ->toHeight($this->target->getHeight())
                ->scale();
    }
}
