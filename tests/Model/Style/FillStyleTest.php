<?php
namespace VectorGraphics\Tests\Model\Style;

use VectorGraphics\Model\Style\FillStyle;

/**
 * @covers VectorGraphics\Model\Style\FillStyle
 *
 * @covers VectorGraphics\Model\Style\AbstractColoredStyle
 */
class FillStyleTest extends AbstractColoredStyleTest
{
    /**
     * @param string $color
     * @param float $opacity
     *
     * @return FillStyle
     */
    protected function createStyle($color = 'black', $opacity = 1.)
    {
        return new FillStyle($color, $opacity);
    }
    
    /**
     */
    public function testConstructor()
    {
        // defaults
        $style = new FillStyle();
        $this->assertSame(1., $style->getOpacity());
        $this->assertNotNull($style->getColor());
        $this->assertSame('black', $style->getColor()->__toString());
        
        $style = new FillStyle('green', 0.6);
        $this->assertSame(0.6, $style->getOpacity());
        $this->assertNotNull($style->getColor());
        $this->assertSame('green', $style->getColor()->__toString());
    }
    
    /**
     */
    public function testSetter()
    {
        $style = new FillStyle();
        $style->setColor('green', 0.5);
        
        $this->assertSame(0.5, $style->getOpacity());
        $this->assertNotNull($style->getColor());
        $this->assertSame('green', $style->getColor()->__toString());

        $style->setColor(null);
        $this->assertSame(0., $style->getOpacity());
        $this->assertNull($style->getColor());

        $style->setColor('blue');
        $this->assertSame(1., $style->getOpacity());
        $this->assertNotNull($style->getColor());
        $this->assertSame('blue', $style->getColor()->__toString());
        
        $style->setOpacity(0.4);
        $this->assertSame(0.4, $style->getOpacity());
    }
    
    /**
     */
    public function testUpdate()
    {
        $style1 = new FillStyle();
        $style2 = new FillStyle('green', 0.6);
        $style1->update($style2);
    
        $this->assertSame(0.6, $style1->getOpacity());
        $this->assertNotNull($style1->getColor());
        $this->assertSame('green', $style1->getColor()->__toString());
    
        // test independence of style1 and style2
        $style1->setOpacity(0.3);
        $this->assertSame(0.3, $style1->getOpacity());
        $this->assertSame(0.6, $style2->getOpacity());
    }
    
    /**
     */
    public function testVisibility()
    {
        $style = new FillStyle();
        $this->assertTrue($style->isVisible());
        
        $style->setOpacity(0);
        $this->assertFalse($style->isVisible());
    
        $style->setOpacity(0.1);
        $this->assertTrue($style->isVisible());
    }
}
