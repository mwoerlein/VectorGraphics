<?php
namespace VectorGraphics\Model\Graphic;

abstract class GraphicElement
{
    /** @var float */
    private $opacity = 1.;

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
        $this->opacity = (float) $opacity;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->getOpacity() > 0.;
    }
    
    /**
     * @param float $angle
     *
     * @return float
     */
    protected function normalizeDegree($angle)
    {
        while ($angle >= 360.) {
            $angle -= 360.;
        }
        while ($angle < 0.) {
            $angle += 360.;
        }
        return $angle;
    }
}
