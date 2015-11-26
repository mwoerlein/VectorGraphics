<?php
namespace VectorGraphics\Tests\Model\Style;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Style\AbstractColoredStyle;

/**
 * @covers VectorGraphics\Model\Style\AbstractColoredStyle
 */
abstract class AbstractColoredStyleTest extends TestCase
{
    /**
     * @param string $color
     * @param float $opacity
     *
     * @return AbstractColoredStyle
     */
    abstract protected function createStyle($color = 'black', $opacity = 1.);
    
    public function testSetColor()
    {
        $style = $this->createStyle();
        
        $style->setColor(null);
        $this->assertNull($style->getColor());
        $this->assertSame(0., $style->getOpacity());
        
        $style->setColor('green');
        $this->assertSame('green', $style->getColor()->__toString());
        $this->assertSame(1., $style->getOpacity());
    
        $style->setColor('#FFF');
        $this->assertSame('#ffffff', $style->getColor()->__toString());
        $this->assertSame(1., $style->getOpacity());
    
        $style->setColor('rgb(17, 34, 51)');
        $this->assertSame('#112233', $style->getColor()->__toString());
        $this->assertSame(1., $style->getOpacity());
    
        $style->setColor('rgba(1, 2, 3, 0.3)');
        $this->assertSame('#010203', $style->getColor()->__toString());
        $this->assertSame(0.3, $style->getOpacity());
        
        $this->setExpectedException(\Exception::class);
        $style->setColor(8);
    }
    
}
