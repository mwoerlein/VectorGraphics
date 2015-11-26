<?php
namespace VectorGraphics\Tests\Model\Shape;

use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\LineTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Shape\Rectangle;

/**
 * @covers VectorGraphics\Model\Shape\Rectangle
 *
 * @covers VectorGraphics\Model\Shape\AbstractShape
 */
class RectangleTest extends AbstractShapeTest
{
    /**
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     *
     * @return Rectangle
     */
    protected function createShape($x = 0., $y = 0., $width = 1., $height = 1.)
    {
        return new Rectangle($x, $y, $width, $height);
    }
    
    /**
     */
    public function testGetter()
    {
        $circle = $this->createShape(1, 2, 3, 4);
        $this->assertEquals(1., $circle->getX());
        $this->assertEquals(2., $circle->getY());
        $this->assertEquals(3., $circle->getWidth());
        $this->assertEquals(4., $circle->getHeight());
    }
    
    /**
     * @return array[]
     */    
    public function getPathProvider()
    {
        $data = [];
        $data['unit square'] = [
            'shape' => $this->createShape(0, 0, 1, 1),
            'path' => [
                new MoveTo(0, 0),
                new LineTo(0, 1),
                new LineTo(1, 1),
                new LineTo(1, 0),
                new Close(0, 0),
            ],
        ];
        $data['some rectange'] = [
            'shape' => $this->createShape(-2, 1, 4, 6),
            'path' => [
                new MoveTo(-2, 1),
                new LineTo(-2, 7),
                new LineTo(2, 7),
                new LineTo(2, 1),
                new Close(-2, 1),
            ],
        ];
        return $data;
    }
}
