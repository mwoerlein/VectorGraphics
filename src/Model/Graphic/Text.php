<?php
namespace VectorGraphics\Model\Graphic;

class Text extends AbstractText
{
    /** @var float */
    private $x;
    
    /** @var float */
    private $y;
    
    /** @var float */
    private $rotation = 0.;
    
    /**
     * @param string $text
     * @param float $x
     * @param float $y
     */
    public function __construct($text, $x, $y)
    {
        parent::__construct($text);
        $this->x = $x;
        $this->y = $y;
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
     * @return float rotation in degree [0, 360[
     */
    public function getRotation()
    {
        return $this->rotation;
    }
    
    /**
     * @param float $rotation in degree
     */
    public function setRotation($rotation)
    {
        while ($rotation >= 360.) {
            $rotation -= 360.;
        }
        while ($rotation < 0.) {
            $rotation += 360.;
        }
        $this->rotation = $rotation;
    }
}
