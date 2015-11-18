<?php
namespace VectorGraphics\Test\Model\Path;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Path;

class PathTest extends TestCase
{
    public function testMoveTo()
    {
        $path = new Path();
        $this->assertEmpty($path->getElements());
        $path->moveTo(1.2, 3.4);
        $elements = $path->getElements();
        $this->assertCount(1, $elements);
        $this->assertInstanceOf(Path\MoveTo::class, $elements[0]);
        $this->assertEquals(1.2, $elements[0]->getDestX());
        $this->assertEquals(3.4, $elements[0]->getDestY());
    }
}
