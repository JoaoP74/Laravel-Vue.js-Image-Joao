<?php

namespace Intervention\Image\Interfaces;

interface SizeInterface
{
    public function width(): int;
    public function height(): int;
    public function pivot(): PointInterface;
    public function withWidth(int $width): SizeInterface;
    public function withHeight(int $height): SizeInterface;
    public function withPivot(PointInterface $pivot): SizeInterface;
    public function getAspectRatio(): float;
    public function fitsInto(SizeInterface $size): bool;
    public function isLandscape(): bool;
    public function isPortrait(): bool;
    public function alignPivot(string $position, int $offset_x = 0, int $offset_y = 0): SizeInterface;
    public function alignPivotTo(SizeInterface $size, string $position): SizeInterface;
    public function getRelativePositionTo(SizeInterface $size): PointInterface;
    public function resize(?int $width = null, ?int $height = null): SizeInterface;
    public function resizeDown(?int $width = null, ?int $height = null): SizeInterface;
    public function scale(?int $width = null, ?int $height = null): SizeInterface;
    public function scaleDown(?int $width = null, ?int $height = null): SizeInterface;
    public function cover(int $width, int $height): SizeInterface;
    public function contain(int $width, int $height): SizeInterface;
}
