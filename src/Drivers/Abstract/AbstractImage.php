<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Collection;
use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\NotWritableException;
use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

abstract class AbstractImage
{
    use CanResolveDriverClass;

    protected $loops = 0;
    protected $frames;

    public function __construct(Collection $frames)
    {
        $this->frames = $frames;
    }

    public function getIterator(): Collection
    {
        return $this->frames;
    }

    public function getFrames(): Collection
    {
        return $this->frames;
    }

    public function getFrame(int $key = 0): ?FrameInterface
    {
        return $this->frames->get($key);
    }

    public function addFrame(FrameInterface $frame): ImageInterface
    {
        $this->frames->push($frame);

        return $this;
    }

    public function setLoops(int $count): ImageInterface
    {
        $this->loops = $count;

        return $this;
    }

    public function loops(): int
    {
        return $this->loops;
    }

    public function getSize(): SizeInterface
    {
        return new Size($this->width(), $this->height());
    }

    public function isAnimated(): bool
    {
        return $this->getFrames()->count() > 1;
    }

    public function modify(ModifierInterface $modifier): ImageInterface
    {
        return $modifier->apply($this);
    }

    public function encode(EncoderInterface $encoder): EncodedImage
    {
        return $encoder->encode($this);
    }

    public function toJpeg(?int $quality = null): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\JpegEncoder', $quality)
        );
    }

    public function toGif(): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\GifEncoder')
        );
    }

    public function toPng(): EncodedImage
    {
        return $this->encode(
            $this->resolveDriverClass('Encoders\PngEncoder')
        );
    }

    public function greyscale(): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\GreyscaleModifier')
        );
    }

    public function blur(int $amount): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass('Modifiers\BlurModifier', $amount)
        );
    }

    public function pickColors(int $x, int $y): Collection
    {
        $colors = new Collection();
        foreach ($this->getFrames() as $key => $frame) {
            $colors->push($this->pickColor($x, $y, $key));
        }

        return $colors;
    }

    public function resize(...$arguments): ImageInterface
    {
        $crop = $this->getSize();
        $resize = Resizer::make()
                ->setTargetSizeByArray($arguments)
                ->resize($crop);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\CropResizeModifier', $crop, $resize)
        );
    }

    public function resizeDown(...$arguments): ImageInterface
    {
        $crop = $this->getSize();
        $resize = Resizer::make()
                ->setTargetSizeByArray($arguments)
                ->resizeDown($crop);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\CropResizeModifier', $crop, $resize)
        );
    }

    public function scale(...$arguments): ImageInterface
    {
        $crop = $this->getSize();
        $resize = Resizer::make()
                ->setTargetSizeByArray($arguments)
                ->scale($crop);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\CropResizeModifier', $crop, $resize)
        );
    }

    public function scaleDown(...$arguments): ImageInterface
    {
        $crop = $this->getSize();
        $resize = Resizer::make()
                ->setTargetSizeByArray($arguments)
                ->scaleDown($crop);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\CropResizeModifier', $size)
        );
    }

    public function fit(int $width, int $height, string $position = 'center'): ImageInterface
    {
        //  crop
        $crop = Resizer::make()
                ->toSize($this->getSize())
                ->contain(new Size($width, $height));
        $crop = Resizer::make()
                ->toSize($crop)
                ->crop($this->getSize(), $position);

        $resize = new Size($width, $height);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\CropResizeModifier', $crop, $resize, $position)
        );
    }

    public function fitDown(int $width, int $height, string $position = 'center'): ImageInterface
    {
        $size = new Size($width, $height);

        return $this->modify(
            $this->resolveDriverClass('Modifiers\CropResizeModifier', $crop, $resize, $position)
        );
    }

    public function place($element, string $position = 'top-left', int $offset_x = 0, int $offset_y = 0): ImageInterface
    {
        return $this->modify(
            $this->resolveDriverClass(
                'Modifiers\PlaceModifier',
                $element,
                $position,
                $offset_x,
                $offset_y
            )
        );
    }
}
