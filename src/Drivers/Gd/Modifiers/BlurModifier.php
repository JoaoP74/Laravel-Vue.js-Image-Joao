<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class BlurModifier implements ModifierInterface
{
    /**
     * Create new modifier
     *
     * @param int $amount Blur amount (0 - 100%)
     */
    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $this->blurFrame($frame);
        }

        return $image;
    }

    protected function blurFrame(FrameInterface $frame): void
    {
        for ($i = 0; $i < $this->amount; $i++) {
            imagefilter($frame->getCore(), IMG_FILTER_GAUSSIAN_BLUR);
        }
    }
}
