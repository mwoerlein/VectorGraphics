<?php
namespace VectorGraphics\Model\Graphic;

abstract class GraphicElement
{
    /** @var float */
    private $opacity = 1;

    /**
     * @return float
     */
    public function getOpacity()
    {
        return $this->opacity;
    }

    /**
     * @param float $opacity
     *
     * @return $this
     */
    public function setOpacity($opacity)
    {
        $this->opacity = $opacity;
        return $this;
    }
}