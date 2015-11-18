<?php
namespace VectorGraphics\Model;

use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\LineTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Path\PathElement;

class Path {
    /** @var PathElement[] */
    private $elements = [];
    
    /**
     * @param PathElement $element
     *
     * @return Path $this
     */
    public function add(PathElement $element)
    {
        $this->elements[] = $element;
        return $this;
    }
    
    /**
     * @param float $x
     * @param float $y
     *
     * @return Path $this
     */
    public function moveTo($x, $y)
    {
        return $this->add(new MoveTo($x, $y));
    }
    
    /**
     * @param float $x
     * @param float $y
     *
     * @return Path $this
     */
    public function lineTo($x, $y)
    {
        return $this->add(new LineTo($x, $y));
    }
    
    /**
     * @param float $cx1
     * @param float $cy1
     * @param float $cx2
     * @param float $cy2
     * @param float $x
     * @param float $y
     *
     * @return Path $this
     */
    public function curveTo($cx1, $cy1, $cx2, $cy2, $x, $y)
    {
        return $this->add(new CurveTo($cx1, $cy1, $cx2, $cy2, $x, $y));
    }
    
    /**
     * @return PathElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }
}
