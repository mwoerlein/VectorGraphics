<?php
namespace VectorGraphics\Model\Shape;

use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Style\FillStyledTrait;
use VectorGraphics\Model\Style\StrokeStyledTrait;

abstract class AbstractShape extends GraphicElement
{
    use StrokeStyledTrait, FillStyledTrait;
    
    /**
     */
    public function __construct()
    {
        $this->initFillStyle(null); // no fill
        $this->initStrokeStyle(1, 'black'); // black stroke
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
