<?php
namespace VectorGraphics\Model\Shape;

use VectorGraphics\Model\Path;

class Rectangle extends AbstractShape
{
    /** @var float */
    private $x;
    
    /** @var float */
    private $y;
    
    /** @var float */
    private $width;
    
    /** @var float */
    private $height;
    
    /**
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     */
    public function __construct($x, $y, $width, $height)
    {
        parent::__construct();
        $this->x = (float) $x;
        $this->y = (float) $y;
        $this->width = (float) $width;
        $this->height = (float) $height;
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
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }
    
    /**
     * @return Path
     */
    public function getPath()
    {
        $x1 = $this->getX();
        $y1 = $this->getY();
        $x2 = $this->getX() + $this->getWidth();
        $y2 = $this->getY() + $this->getHeight();
        return (new Path())
            ->moveTo($x1, $y1)
            ->lineTo($x1, $y2)
            ->lineTo($x2, $y2)
            ->lineTo($x2, $y1)
            ->close();
    }
}
