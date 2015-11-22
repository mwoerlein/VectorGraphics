<?php
namespace VectorGraphics\Model;

use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Graphic\Viewport;

class Graphic
{
    /** @var GraphicElement[] */
    private $elements = [];
    
    /** @var Viewport */
    private $viewport;
    
    public function __construct()
    {
        $this->setViewportCorners(0., 0., 1000., 1000.);
    }
    
    /**
     * @param GraphicElement $element
     *
     * @return $this
     */
    public function add(GraphicElement $element) {
        $this->elements[] = $element;
        return $this;
    }
    
    /**
     * @return GraphicElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }
    
    /**
     * @return Viewport
     */
    public function getViewport()
    {
        return $this->viewport;
    }
    
    /**
     * @param Viewport $viewport
     *
     * @return $this
     */
    public function setViewport(Viewport $viewport) {
        $this->viewport = $viewport;
        return $this;
    }
    
    /**
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     *
     * @return $this
     */
    public function setViewportCorners($x1, $y1, $x2, $y2) {
        return $this->setViewport(new Viewport($x1, $y1, $x2 - $x1, $y2 - $y1));
    }
}
