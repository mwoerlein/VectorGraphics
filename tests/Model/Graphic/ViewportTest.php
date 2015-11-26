<?php
namespace VectorGraphics\Tests\Model\Graphic;

use VectorGraphics\Model\Graphic\Viewport;
use VectorGraphics\Tests\Model\Shape\RectangleTest;

/**
 * @covers VectorGraphics\Model\Graphic\Viewport
 */
class ViewportTest extends RectangleTest
{
    /**
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     *
     * @return Viewport
     */
    protected function createShape($x = 0., $y = 0., $width = 1., $height = 1.)
    {
        return new Viewport($x, $y, $width, $height);
    }
    
    public function testBase()
    {
        $viewport = $this->createShape(1, 2, 3, 4);
        $this->assertEquals(5, $viewport->getXBase());
        $this->assertEquals(8, $viewport->getYBase());
    }
    
}
