<?php
namespace VectorGraphics\Model;

use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\LineTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Path\PathElement;

class Path {
    /** @var PathElement[] */
    private $elements = [];
    
    /** @var MoveTo */
    private $lastMoveTo;
    
    /** @var bool */
    private $visible = false;
    
    /**
     * @param float $x
     * @param float $y
     */
    public function __construct($x, $y)
    {
        $this->moveTo($x, $y);
    }
    
    /**
     * @param float $x
     * @param float $y
     *
     * @return Path $this
     */
    public function moveTo($x, $y)
    {
        return $this->add($this->lastMoveTo = new MoveTo($x, $y));
    }
    
    /**
     * @param float $x
     * @param float $y
     *
     * @return Path $this
     */
    public function lineTo($x, $y)
    {
        $this->visible = true;
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
        $this->visible = true;
        return $this->add(new CurveTo($cx1, $cy1, $cx2, $cy2, $x, $y));
    }
    
    /**
     * @return Path $this
     * @throws \Exception
     */
    public function close()
    {
        $this->add(new Close($this->lastMoveTo->getDestX(), $this->lastMoveTo->getDestY()));
        $this->lastMoveTo = null;
        return $this;
    }
    
    /**
     * @return PathElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }
    
    /**
     * @param PathElement $element
     *
     * @return Path $this
     */
    private function add(PathElement $element)
    {
        $this->elements[] = $element;
        return $this;
    }
}
