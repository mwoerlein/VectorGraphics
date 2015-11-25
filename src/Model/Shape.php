<?php
namespace VectorGraphics\Model;

use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Style\FillStyledTrait;
use VectorGraphics\Model\Style\StrokeStyledTrait;

abstract class Shape extends GraphicElement
{
    use StrokeStyledTrait, FillStyledTrait;
    
    /**
     */
    public function __construct()
    {
        $this->initFillStyle(); // no fill
        $this->initStrokeStyle(1, "black"); // black stroke
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return parent::isVisible()
        && ($this->getFillStyle()->isVisible() || $this->getStrokeStyle()->isVisible());
    }
    
    /**
     * @return Path
     */
    abstract public function getPath();
}
