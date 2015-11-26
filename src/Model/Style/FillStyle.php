<?php
namespace VectorGraphics\Model\Style;

class FillStyle extends AbstractColoredStyle
{
    /**
     * @param string|null $color
     * @param float $opacity
     */
    public function __construct($color = null, $opacity = 1.) {
        $this->setColor($color, $opacity);
    }
    
    /**
     * @param FillStyle $style
     */
    public function update(FillStyle $style)
    {
        $this->setColor($style->getColor(), $style->getOpacity());
    }
}