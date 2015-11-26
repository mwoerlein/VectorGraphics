<?php
namespace VectorGraphics\Model\Shape;

use VectorGraphics\Model\Path;

class Circle extends AbstractShape
{
    /** @var float */
    private $x;
    
    /** @var float */
    private $y;
    
    /** @var float */
    private $radius;
    
    /**
     * @param float $x
     * @param float $y
     * @param float $radius
     */
    public function __construct($x, $y, $radius)
    {
        parent::__construct();
        $this->x = $x;
        $this->y = $y;
        $this->radius = $radius;
    }
    
    /**
     * @return float
     */
    public function getX()
    {
        return $this->x;
    }
    
    /**
     * @return float
     */
    public function getY()
    {
        return $this->y;
    }
    
    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }
    
    /**
     * @return Path
     */
    public function getPath()
    {
        $x = $this->getX();
        $y = $this->getY();
        $r = $this->getRadius();
        $s = $r * 4. * (sqrt(2.) - 1.) / 3.;
        return (new Path())
            ->moveTo($x, $y+$r)
            ->curveTo($x+$s, $y+$r, $x+$r, $y+$s, $x+$r, $y)
            ->curveTo($x+$r, $y-$s, $x+$s, $y-$r, $x, $y-$r)
            ->curveTo($x-$s, $y-$r, $x-$r, $y-$s, $x-$r, $y)
            ->curveTo($x-$r, $y+$s, $x-$s, $y+$r, $x, $y+$r)
            ->close();
    }
}