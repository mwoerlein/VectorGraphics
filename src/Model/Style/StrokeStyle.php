<?php
namespace VectorGraphics\Model\Style;

class StrokeStyle extends AbstractColoredStyle
{
    /** @var float */
    private $width;
    
    /**
     * @param float $width
     * @param HtmlColor|string|null $color
     * @param float $opacity
     */
    public function __construct($width = 1., $color = null, $opacity = 1.) {
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
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->getWidth() > 0. && parent::isVisible();
    }
    
    /**
     * @param StrokeStyle $style
     */
    public function update(StrokeStyle $style)
    {
        $this->setColor($style->getColor(), $style->getOpacity());
        $this->setWidth($style->getWidth());
    }
}
