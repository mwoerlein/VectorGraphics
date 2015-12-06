<?php
namespace VectorGraphics\Tests\Model;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Path;

/**
 * @covers VectorGraphics\Model\Path
 * @covers VectorGraphics\Model\Path\Close
 * @covers VectorGraphics\Model\Path\CurveTo
 * @covers VectorGraphics\Model\Path\LineTo
 * @covers VectorGraphics\Model\Path\MoveTo
 * @covers VectorGraphics\Model\Path\PathElement
 */
class PathTest extends TestCase
{
    /**
     */
    public function testAddMultipleAndGet()
    {
        $elements = [
            new Path\MoveTo(1, 1),
            new Path\LineTo(1, 0),
            new Path\CurveTo(1, 0.55, 0.55, 1, 0, 1),
            new Path\Close(1, 1),
        ];
        $path = new Path(1, 1);
        $this->assertCount(1, $path->getElements());
        $path->lineTo(1, 0);
        $this->assertCount(2, $path->getElements());
        $path->curveTo(1, 0.55, 0.55, 1, 0, 1);
        $this->assertCount(3, $path->getElements());
        $path->close();
        $this->assertCount(4, $path->getElements());
        
        $this->assertEquals($elements, $path->getElements());
    }
    
    /**
     */
    public function testMoveTo()
    {
        $path = new Path(0, 0);
        $path->moveTo(1.2, 3.4);
        
        $elements = $path->getElements();
        $this->assertCount(2, $elements);
        $this->assertInstanceOf(Path\MoveTo::class, $elements[0]);
        $this->assertEquals(0., $elements[0]->getDestX());
        $this->assertEquals(0., $elements[0]->getDestY());
        $this->assertInstanceOf(Path\MoveTo::class, $elements[1]);
        $this->assertEquals(1.2, $elements[1]->getDestX());
        $this->assertEquals(3.4, $elements[1]->getDestY());
    }
    
    /**
     */
    public function testLineTo()
    {
        $path = new Path(0, 0);
        $path->lineTo(2.3, 4.5);
        
        $elements = $path->getElements();
        $this->assertCount(2, $elements);
        $this->assertInstanceOf(Path\LineTo::class, $elements[1]);
        $this->assertEquals(2.3, $elements[1]->getDestX());
        $this->assertEquals(4.5, $elements[1]->getDestY());
    }
    
    /**
     */
    public function testCurveTo()
    {
        $path = new Path(0, 0);
        $path->curveTo(1.2, 2.3, 3.4, 4.5, 5.6, 6.7);
        
        $elements = $path->getElements();
        $this->assertCount(2, $elements);
        $this->assertInstanceOf(Path\CurveTo::class, $elements[1]);
        /** @var Path\CurveTo $element */
        $element = $elements[1];
        $this->assertEquals(1.2, $element->getControl1X());
        $this->assertEquals(2.3, $element->getControl1Y());
        $this->assertEquals(3.4, $element->getControl2X());
        $this->assertEquals(4.5, $element->getControl2Y());
        $this->assertEquals(5.6, $element->getDestX());
        $this->assertEquals(6.7, $element->getDestY());
    }
    
    /**
     */
    public function testCloseLastMoveTo()
    {
        $path = new Path(1, 2);
        $path->moveTo(2, 3);
        $path->moveTo(3, 4);
        $path->close();
        
        $elements = $path->getElements();
        $this->assertCount(4, $elements);
        $this->assertEquals(3, $elements[3]->getDestX());
        $this->assertEquals(4, $elements[3]->getDestY());
    }
    
    /**
     */
    public function testVisibility()
    {
        $path = new Path(1, 2);
        $this->assertFalse($path->isVisible());
        
        $path = new Path(1, 2);
        $path->close();
        $this->assertFalse($path->isVisible());
        
        $path = new Path(1, 2);
        $path->lineTo(2, 3);
        $this->assertTrue($path->isVisible());
        
        $path = new Path(1, 2);
        $path->curveTo(2, 3, 3, 4, 4, 5);
        $this->assertTrue($path->isVisible());
    }
}
