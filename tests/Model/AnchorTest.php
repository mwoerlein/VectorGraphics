<?php
namespace VectorGraphics\Tests\Model;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Anchor;

/**
 * @covers VectorGraphics\Model\Anchor
 */
class AnchorTest extends TestCase
{
    public function testConstructor()
    {
        $anchor = new Anchor(1, 2, 3, 3);
        $this->assertSame(1., $anchor->x);
        $this->assertSame(2., $anchor->y);
        $this->assertSame(sqrt(2.)/2., $anchor->tangentX);
        $this->assertSame(sqrt(2.)/2., $anchor->tangentY);
        
        $anchor = new Anchor(5, 6);
        $this->assertSame(5., $anchor->x);
        $this->assertSame(6., $anchor->y);
        $this->assertSame(1., $anchor->tangentX);
        $this->assertSame(0., $anchor->tangentY);
    
        $this->setExpectedException(InvalidArgumentException::class);
        new Anchor(1, 1, 0, 0);
    }
    
    public function distanceProvider()
    {
        $data = [];
        
        $data['same anchor'] = [
            'anchor1' => new Anchor(1, 1),
            'anchor2' => new Anchor(1, 1),
            'distance' => 0.,
        ];
    
        $data['3, 4, 5'] = [
            'anchor1' => new Anchor(0, 0),
            'anchor2' => new Anchor(3, 4),
            'distance' => 5.,
        ];
        $data['-3, 4, 5'] = [
            'anchor1' => new Anchor(-3, 0),
            'anchor2' => new Anchor(0, 4),
            'distance' => 5.,
        ];
        $data['2, 2'] = [
            'anchor1' => new Anchor(1, 2),
            'anchor2' => new Anchor(3, 4),
            'distance' => 2 * sqrt(2),
        ];
        return $data;
    }
    
    /**
     * @param Anchor $anchor1
     * @param Anchor $anchor2
     * @param float $distance
     * 
     * @dataProvider distanceProvider
     */
    public function testDistance(Anchor $anchor1, Anchor $anchor2, $distance)
    {
        $this->assertSame($distance, $anchor1->getDistanceTo($anchor2));
        $this->assertSame($distance, $anchor2->getDistanceTo($anchor1));
    }
    
    public function rotationProvider()
    {
        $data = [];
        
        $data['no rotation'] = [
            'anchor' => new Anchor(1, 1),
            'rotation' => 0.,
        ];
        
        $data['45°'] = [
            'anchor' => new Anchor(1, 1, 1, -1),
            'rotation' => 45.,
        ];
        
        $data['90°'] = [
            'anchor' => new Anchor(1, 1, 0, -1),
            'rotation' => 90.,
        ];
        
        $data['135°'] = [
            'anchor' => new Anchor(1, 1, -1, -1),
            'rotation' => 135.,
        ];
        
        $data['180°'] = [
            'anchor' => new Anchor(1, 1, -1, 0),
            'rotation' => 180.,
        ];
        
        $data['225°'] = [
            'anchor' => new Anchor(1, 1, -1, 1),
            'rotation' => 225.,
        ];
        
        $data['315°'] = [
            'anchor' => new Anchor(1, 1, 1, 1),
            'rotation' => 315.,
        ];
        return $data;
    }
    
    /**
     * @param Anchor $anchor
     * @param float $rotation
     *
     * @dataProvider rotationProvider
     */
    public function testRotation(Anchor $anchor, $rotation)
    {
        $this->assertSame($rotation, $anchor->getRotation());
    }
}
