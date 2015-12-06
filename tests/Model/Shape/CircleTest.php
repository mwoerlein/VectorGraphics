<?php
namespace VectorGraphics\Tests\Model\Shape;

use InvalidArgumentException;
use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Shape\Circle;

/**
 * @covers VectorGraphics\Model\Shape\Circle
 *
 * @covers VectorGraphics\Model\Shape\AbstractShape
 */
class CircleTest extends AbstractShapeTest
{
    /**
     * @param float $x
     * @param float $y
     * @param float $r
     *
     * @return Circle
     */
    protected function createShape($x = 0., $y = 0., $r = 1.)
    {
        return new Circle($x, $y, $r);
    }
    
    /**
     */
    public function testGetter()
    {
        $circle = $this->createShape(1, 2, 3);
        $this->assertEquals(1., $circle->getX());
        $this->assertEquals(2., $circle->getY());
        $this->assertEquals(3., $circle->getRadius());
    }
    
    /**
     * @return array[]
     */
    public function getPathProvider()
    {
        $data = [];
        $data['unit circle'] = [
            'shape' => $this->createShape(0, 0, 1),
            'path' => [
                new MoveTo(0, 1),
                new CurveTo(0.55228474983079356, 1, 1, 0.55228474983079356, 1, 0),
                new CurveTo(1, -0.55228474983079356, 0.55228474983079356, -1, 0, -1),
                new CurveTo(-0.55228474983079356, -1, -1, -0.55228474983079356, -1, 0),
                new CurveTo(-1, 0.55228474983079356, -0.55228474983079356, 1, 0, 1),
                new Close(0, 1),
            ],
        ];
        $data['translated'] = [
            'shape' => $this->createShape(1, 2, 3),
            'path' => [
                new MoveTo(1, 5),
                new CurveTo(2.6568542494923806, 5, 4, 3.6568542494923806, 4, 2),
                new CurveTo(4, 0.34314575050761942, 2.6568542494923806, -1, 1, -1),
                new CurveTo(-0.65685424949238058, -1, -2, 0.34314575050761942, -2, 2),
                new CurveTo(-2, 3.6568542494923806, -0.65685424949238058, 5, 1, 5),
                new Close(1, 5),
            ],
        ];
        return $data;
    }
    
    /**
     * @return array[]
     */
    public function invalidConstructorProvider()
    {
        $data = [];
        $data['radius 0'] = [
            'x' => 0,
            'y' => 0,
            'r' => 0,
        ];
        $data['negative radius'] = [
            'x' => 0,
            'y' => 0,
            'r' => -3,
        ];
        return $data;
    }
    
    /**
     * @param float $x
     * @param float $y
     * @param float $radius
     * 
     * @dataProvider invalidConstructorProvider
     */
    public function testInvalidConstructor($x, $y, $radius)
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->createShape($x, $y, $radius);
    }
}
