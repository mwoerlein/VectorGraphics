<?php
namespace VectorGraphics\Model\Path;

class Path {
    /** @var PathElement[] */
    private $elements = [];
    
    /**
     * @param PathElement $element
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
     * @return Path $this
     */
    public function moveTo($x, $y)
    {
        return $this->add(new MoveTo($x, $y));
    }
    
    /**
     * @param float $x
     * @param float $y
     * @return Path $this
     */
    public function lineTo($x, $y)
    {
        return $this->add(new LineTo($x, $y));
    }
    
    /**
     * @param float $x
     * @param float $y
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

