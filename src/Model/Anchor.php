<?php
namespace VectorGraphics\Model;

use InvalidArgumentException;

class Anchor
{
    /** @var float */
    public $x;
    
    /** @var float */
    public $y;
    
    /** @var float */
    public $tangentX;
    
    /** @var float */
    public $tangentY;
    
    /**
     * @param float $x
     * @param float $y
     * @param float $tx
     * @param float $ty
     */
    public function __construct($x, $y, $tx = 1., $ty = 0.)
    {
        if ($tx == 0. && $ty == 0.) {
            throw new InvalidArgumentException('tangent has to have any direction');
        }
        $this->x = (float) $x;
        $this->y = (float) $y;
        $tl = sqrt($tx * $tx + $ty * $ty);
        $this->tangentX = (float) $tx / $tl;
        $this->tangentY = (float) $ty / $tl;
    }
    
    /**
     * @param Anchor $anchor
     *
     * @return float
     */
    public function getDistanceTo(Anchor $anchor)
    {
        return sqrt(
            ($this->x - $anchor->x) * ($this->x - $anchor->x)
            + ($this->y - $anchor->y) * ($this->y - $anchor->y)
        );
    }
    
    /**
     * @return float
     */
    public function getRotation()
    {
        $rotation = -180. * atan2($this->tangentY, $this->tangentX) / pi();
        return $rotation >= 0. ? $rotation : $rotation + 360.;
    }
}
