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
        $this->destX = $destX;
        $this->destY = $destY;
    }
    
    /**
     * @return int
     */
    public function getDestX()
    {
        return $this->destX;
    }
    
    /**
     * @return int
     */
    public function getDestY()
    {
        return $this->destY;
    }
}

