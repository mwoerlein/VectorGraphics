<?php
namespace VectorGraphics\Model\Style;

class StrokeStyle extends AbstractColoredStyle
{
    /** @var float */
    private $width = 0;
    
    /**
     * @param int $width
     * @param string|null $color
     * @param float $opacity
     */
    public function __construct($width = 1, $color = null, $opacity = 0.) {
        $this->setColor($color, $opacity);
        $this->setWidth($width);
    }
    
    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * @param float $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->getWidth() > 0. && parent::isVisible();
    }
}