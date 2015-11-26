<?php
namespace VectorGraphics\Model\Path;

abstract class PathElement {
    
    /** @var float */
    protected $destX;
    
    /** @var float */
    protected $destY;
    
    /**
     * @param float $destX
     * @param float $destY
     */
    public function __construct($destX, $destY)
    {
        $this->destX = (float) $destX;
        $this->destY = (float) $destY;
    }
    
    /**
     * @return float
     */
    public function getDestX()
    {
        return $this->destX;
    }
    
    /**
     * @return float
     */
    public function getDestY()
    {
        return $this->destY;
    }
}
