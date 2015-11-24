<?php
namespace VectorGraphics\Model\Style;

class FillStyle extends AbstractColoredStyle
{
    /**
     * @param string|null $color
     * @param float $opacity
     */
    public function __construct($color = null, $opacity = 0.) {
        $this->setColor($color, $opacity);
    }
}