<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->getTextBlock();
        $boundingBox = $lines->getBoundingBox($this->getFont(), $this->position);
        $lines->alignByFont($this->getFont(), $boundingBox->last());

        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    imagettftext(
                        $frame->getCore(),
                        $this->getFont()->getSize(),
                        $this->getFont()->getAngle() * (-1),
                        $line->getPosition()->getX(),
                        $line->getPosition()->getY(),
                        $this->getFont()->getColor()->toInt(),
                        $this->getFont()->getFilename(),
                        $line
                    );
                }

                // debug
                imagepolygon($frame->getCore(), $boundingBox->toArray(), 0);
            } else {
                foreach ($lines as $line) {
                    imagestring(
                        $frame->getCore(),
                        $this->getFont()->getGdFont(),
                        $line->getPosition()->getX(),
                        $line->getPosition()->getY(),
                        $line,
                        $this->font->getColor()->toInt()
                    );
                    imagepolygon($frame->getCore(), $boundingBox->toArray(), 0);
                }
            }
        }

        return $image;
    }

    private function getFont(): Font
    {
        if (!is_a($this->font, Font::class)) {
            throw new FontException('Font is not compatible to current driver.');
        }
        return $this->font;
    }
}
