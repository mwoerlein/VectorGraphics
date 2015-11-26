<?php
namespace VectorGraphics\Tests\Model\Shape;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Path\PathElement;
use VectorGraphics\Model\Shape\AbstractShape;

/**
 * @covers VectorGraphics\Model\Graphic\GraphicElement
 * @covers VectorGraphics\Model\Shape\AbstractShape
 * @covers VectorGraphics\Model\Style\FillStyledTrait
 * @covers VectorGraphics\Model\Style\StrokeStyledTrait
 */
abstract class AbstractShapeTest extends TestCase
{
    /**
     * @return AbstractShape
     */
    abstract protected function createShape();
    
    /**
     * @return array[]
     */
    abstract protected function getPathProvider();
    
    /**
     * @return array[]
     */
    public function visibilityProvider()
    {
        $data = [];
        
        $data['opaque, but visible'] = [
            'modify' => [
                'opacity' => [0.1]
            ],
            'visible' => true,
        ];
        $data['opaque, not visible'] = [
            'modify' => [
                'opacity' => [0]
            ],
            'visible' => false,
        ];
        
        $data['no color'] = [
            'modify' => [
                'fillColor' => [null],
                'strokeColor' => [null],
            ],
            'visible' => false,
        ];
        $data['just stroke'] = [
            'modify' => [
                'fillColor' => [null],
                'strokeColor' => ['black'],
            ],
            'visible' => true,
        ];
        $data['just fill'] = [
            'modify' => [
                'fillColor' => ['black'],
                'strokeColor' => [null],
            ],
            'visible' => true,
        ];
        $data['opaque colors'] = [
            'modify' => [
                'fillColor' => ['black', 0],
                'strokeColor' => ['black', 0],
            ],
            'visible' => false,
        ];
        $data['opaque colors, but visible'] = [
            'modify' => [
                'fillOpacity' => [0.1],
                'strokeOpacity' => [0.1],
            ],
            'visible' => true,
        ];
        
        $data['no fill, no stroke'] = [
            'modify' => [
                'fillColor' => [null],
                'strokeColor' => ['black'],
                'strokeWidth' => [0],
            ],
            'visible' => false,
        ];
        
        return $data;
    }
    
    /**
     * @param array[] $modify
     * @param bool $visible
     *
     * @dataProvider visibilityProvider
     */
    public function testVisibility(array $modify, $visible)
    {
        $text = $this->createShape();
        foreach ($modify as $type => $args) {
            call_user_func_array([$text, 'set' . ucfirst($type)], $args);
        }
        $this->assertSame($visible, $text->isVisible());
    }
    
    /*
     */
    public function testFillSetter()
    {
        $shape = $this->createShape();
        $fillStyle = $shape->getFillStyle();
        // default color for text: 'black'
        $this->assertNull($fillStyle->getColor());
        $this->assertSame(0., $fillStyle->getOpacity());
        
        $shape->setFillColor('green', 0.8);
        $this->assertSame('green', $fillStyle->getColor()->__toString());
        $this->assertSame(0.8, $fillStyle->getOpacity());
        
        $shape->setFillOpacity(0.5);
        $this->assertSame('green', $fillStyle->getColor()->__toString());
        $this->assertSame(0.5, $fillStyle->getOpacity());
        
        $shape->setFillColor('red');
        $this->assertSame('red', $fillStyle->getColor()->__toString());
        $this->assertSame(1., $fillStyle->getOpacity());
    }
    
    /*
     */
    public function testStrokeSetter()
    {
        $shape = $this->createShape();
        $strokeStyle = $shape->getStrokeStyle();
        // default color for text: none
        $this->assertSame('black', $strokeStyle->getColor()->__toString());
        $this->assertSame(1., $strokeStyle->getOpacity());
        
        $shape->setStrokeColor('green', 0.8);
        $this->assertNotNull($strokeStyle->getColor());
        $this->assertSame('green', $strokeStyle->getColor()->__toString());
        $this->assertSame(0.8, $strokeStyle->getOpacity());
        
        $shape->setStrokeOpacity(0.5);
        $this->assertNotNull($strokeStyle->getColor());
        $this->assertSame('green', $strokeStyle->getColor()->__toString());
        $this->assertSame(0.5, $strokeStyle->getOpacity());
        
        $shape->setStrokeColor('red');
        $this->assertNotNull($strokeStyle->getColor());
        $this->assertSame('red', $strokeStyle->getColor()->__toString());
        
        // default width for text: 1.
        $this->assertSame(1., $strokeStyle->getWidth());
        
        $shape->setStrokeWidth(15.9);
        $this->assertSame(15.9, $strokeStyle->getWidth());
    }
    
    /**
     * @dataProvider getPathProvider
     *
     * @param AbstractShape $shape
     * @param Path|PathElement[] $path
     */
    public function testGetPath($shape, $path)
    {
        if ($path instanceof Path) {
            $this->assertSame($path, $shape->getPath());
        } else {
            $this->assertEquals($path, $shape->getPath()->getElements());
        }
    }
}
