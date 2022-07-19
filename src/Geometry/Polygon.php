<?php

namespace Intervention\Image\Geometry;

use ArrayAccess;
use Countable;

class Polygon implements Countable, ArrayAccess
{
    public function __construct(
        protected array $points = [],
        protected ?Point $pivot = null
    ) {
        $this->pivot = $pivot ? $pivot : new Point();
    }

    /**
     * Return current pivot point
     *
     * @return Point
     */
    public function getPivotPoint(): Point
    {
        return $this->pivot;
    }

    /**
     * Change pivot point to given point
     *
     * @param Point $pivot
     * @return Polygon
     */
    public function setPivotPoint(Point $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }

    /**
     * Return first point of polygon
     *
     * @return ?Point
     */
    public function first(): ?Point
    {
        if ($point = reset($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return last point of polygon
     *
     * @return ?Point
     */
    public function last(): ?Point
    {
        if ($point = end($this->points)) {
            return $point;
        }

        return null;
    }

    /**
     * Return polygon's point count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->points);
    }

    /**
     * Determine if point exists at given offset
     *
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->points);
    }

    /**
     * Return point at given offset
     *
     * @param mixed $offset
     * @return Point
     */
    public function offsetGet($offset)
    {
        return $this->points[$offset];
    }

    /**
     * Set point at given offset
     *
     * @param mixed $offset
     * @param Point $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->points[$offset] = $value;
    }

    /**
     * Unset offset at given offset
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->points[$offset]);
    }

    /**
     * Add given point to polygon
     *
     * @param Point $point
     * @return Polygon
     */
    public function addPoint(Point $point): self
    {
        $this->points[] = $point;

        return $this;
    }

    /**
     * Calculate total horizontal span of polygon
     *
     * @return int
     */
    public function width(): int
    {
        return abs($this->getMostLeftPoint()->getX() - $this->getMostRightPoint()->getX());
    }

    /**
     * Calculate total vertical span of polygon
     *
     * @return int
     */
    public function height(): int
    {
        return abs($this->getMostBottomPoint()->getY() - $this->getMostTopPoint()->getY());
    }

    /**
     * Return most left point of all points in polygon
     *
     * @return Point
     */
    public function getMostLeftPoint(): Point
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $point;
        }

        usort($points, function ($a, $b) {
            if ($a->getX() === $b->getX()) {
                return 0;
            }
            return ($a->getX() < $b->getX()) ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Return most right point in polygon
     *
     * @return Point
     */
    public function getMostRightPoint(): Point
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $point;
        }

        usort($points, function ($a, $b) {
            if ($a->getX() === $b->getX()) {
                return 0;
            }
            return ($a->getX() > $b->getX()) ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Return most top point in polygon
     *
     * @return Point
     */
    public function getMostTopPoint(): Point
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $point;
        }

        usort($points, function ($a, $b) {
            if ($a->getY() === $b->getY()) {
                return 0;
            }
            return ($a->getY() > $b->getY()) ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Return most bottom point in polygon
     *
     * @return Point
     */
    public function getMostBottomPoint(): Point
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $point;
        }

        usort($points, function ($a, $b) {
            if ($a->getY() === $b->getY()) {
                return 0;
            }
            return ($a->getY() < $b->getY()) ? -1 : 1;
        });

        return $points[0];
    }

    /**
     * Create and return point in absolute center of the polygon
     *
     * @return Point
     */
    public function getCenterPoint(): Point
    {
        return new Point(
            $this->getMostRightPoint()->getX() - (intval(round($this->width() / 2))),
            $this->getMostTopPoint()->getY() - (intval(round($this->height() / 2)))
        );
    }

    /**
     * Align all points of polygon horizontally to given position around pivot point
     *
     * @param string $position
     * @return Polygon
     */
    public function align(string $position): self
    {
        switch (strtolower($position)) {
            case 'center':
            case 'middle':
                $diff = ($this->getCenterPoint()->getX() - $this->pivot->getX());
                break;

            case 'right':
                $diff = ($this->getMostRightPoint()->getX() - $this->pivot->getX());
                break;

            default:
            case 'left':
                $diff = ($this->getMostLeftPoint()->getX() - $this->pivot->getX());
                break;
        }

        foreach ($this->points as $point) {
            $point->setX($point->getX() - $diff);
        }

        return $this;
    }

    /**
     * Align all points of polygon vertically to given position around pivot point
     *
     * @param string $position
     * @return Polygon
     */
    public function valign(string $position): self
    {
        switch (strtolower($position)) {
            case 'center':
            case 'middle':
                $diff = ($this->getCenterPoint()->getY() - $this->pivot->getY());
                break;

            case 'top':
                $diff = ($this->getMostTopPoint()->getY() - $this->pivot->getY()) - $this->height();
                break;

            default:
            case 'bottom':
                $diff = ($this->getMostBottomPoint()->getY() - $this->pivot->getY()) + $this->height();
                break;
        }

        foreach ($this->points as $point) {
            $point->setY($point->getY() - $diff);
        }

        return $this;
    }

    /**
     * Rotate points of polygon around pivot point with given angle
     *
     * @param float $angle
     * @return Polygon
     */
    public function rotate(float $angle): self
    {
        $sin = sin(deg2rad($angle));
        $cos = cos(deg2rad($angle));

        foreach ($this->points as $point) {
            // translate point to pivot
            $point->setX($point->getX() - $this->pivot->getX());
            $point->setY($point->getY() - $this->pivot->getY());

            // rotate point
            $x = $point->getX() * $cos - $point->getY() * $sin;
            $y = $point->getX() * $sin + $point->getY() * $cos;

            // translate point back
            $point->setX($x + $this->pivot->getX());
            $point->setY($y + $this->pivot->getY());
        }

        return $this;
    }

    /**
     * Move all points by given amount on the x-axis
     *
     * @param  int $amount
     * @return Polygon
     */
    public function movePointsX(int $amount): self
    {
        foreach ($this->points as $point) {
            $point->moveX($amount);
        }

        return $this;
    }

    /**
     * Move all points by given amount on the y-axis
     *
     * @param int $amount
     * @return Polygon
     */
    public function movePointsY(int $amount): self
    {
        foreach ($this->points as $point) {
            $point->moveY($amount);
        }

        return $this;
    }

    /**
     * Return array of all x/y values of all points of polygon
     *
     * @return array
     */
    public function toArray(): array
    {
        $coordinates = [];
        foreach ($this->points as $point) {
            $coordinates[] = $point->getX();
            $coordinates[] = $point->getY();
        }

        return $coordinates;
    }
}
