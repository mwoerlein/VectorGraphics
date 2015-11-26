<?php
namespace VectorGraphics\Tests\Model;

use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Graphic\Viewport;
use VectorGraphics\Tests\Model\Graphic\AbstractElementContainerTest;

/**
 * @covers VectorGraphics\Model\Graphic
 * 
 * @covers VectorGraphics\Model\Graphic\AbstractElementContainer
 */
class GraphicTest extends AbstractElementContainerTest
{
    /**
     * @return Graphic
     */
    protected function createContainer()
    {
        return new Graphic();
    }
    
    public function testGetAndSetViewport()
    {
        $graphic = $this->createContainer();
        // default: 0, 0, 1000, 1000
        $this->assertSame(0., $graphic->getViewport()->getX());
        $this->assertSame(0., $graphic->getViewport()->getY());
        $this->assertSame(1000., $graphic->getViewport()->getWidth());
        $this->assertSame(1000., $graphic->getViewport()->getHeight());
        
        $graphic->setViewportCorners(-20, 15, 30, 40);
        $this->assertSame(-20., $graphic->getViewport()->getX());
        $this->assertSame(15., $graphic->getViewport()->getY());
        $this->assertSame(50., $graphic->getViewport()->getWidth());
        $this->assertSame(25., $graphic->getViewport()->getHeight());
    
        $viewport = new Viewport(1, 2, 3, 4);
        $graphic->setViewport($viewport);
        $this->assertSame($viewport, $graphic->getViewport());
    }
}
