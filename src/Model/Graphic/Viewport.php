<?php
namespace VectorGraphics\Model\Graphic;

use VectorGraphics\Model\Shape\Rectangle;

class Viewport extends Rectangle
{
    /**
     * @return float
     */
    public function getYBase()
    {
        return 2. * $this->getY() + $this->getHeight();
    }

    /**
     * @return float
     */
    public function getXBase()
    {
        return 2. * $this->getX() + $this->getWidth();
    }
}