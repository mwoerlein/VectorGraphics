<?php
namespace VectorGraphics\Tests\Model\Style;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Style\FontStyle;

/**
 * @covers VectorGraphics\Model\Style\FontStyle
 */
class FontStyleTest extends TestCase
{
    /**
     */
    public function testConstructor()
    {
        // defaults
        $style = new FontStyle();
        $this->assertSame(12, $style->getSize());
        $this->assertSame(FontStyle::FONT_TIMES, $style->getName());
        $this->assertSame(FontStyle::FONT_STYLE_NORMAL, $style->getStyle());
        $this->assertSame(FontStyle::HORIZONTAL_ALIGN_LEFT, $style->getHAlign());
        $this->assertSame(FontStyle::VERTICAL_ALIGN_BASE, $style->getVAlign());
        
        $style = new FontStyle(
            8,
            FontStyle::FONT_COURIER,
            FontStyle::FONT_STYLE_BOLD,
            FontStyle::HORIZONTAL_ALIGN_MIDDLE,
            FontStyle::VERTICAL_ALIGN_CENTRAL
        );
        $this->assertSame(8, $style->getSize());
        $this->assertSame(FontStyle::FONT_COURIER, $style->getName());
        $this->assertSame(FontStyle::FONT_STYLE_BOLD, $style->getStyle());
        $this->assertSame(FontStyle::HORIZONTAL_ALIGN_MIDDLE, $style->getHAlign());
        $this->assertSame(FontStyle::VERTICAL_ALIGN_CENTRAL, $style->getVAlign());
    }
    
    /**
     */
    public function testSetter()
    {
        $style = new FontStyle();
        $style->setSize(8)
            ->setName(FontStyle::FONT_COURIER)
            ->setStyle(FontStyle::FONT_STYLE_BOLD)
            ->setHAlign(FontStyle::HORIZONTAL_ALIGN_MIDDLE)
            ->setVAlign(FontStyle::VERTICAL_ALIGN_CENTRAL);
    
        $this->assertSame(8, $style->getSize());
        $this->assertSame(FontStyle::FONT_COURIER, $style->getName());
        $this->assertSame(FontStyle::FONT_STYLE_BOLD, $style->getStyle());
        $this->assertSame(FontStyle::HORIZONTAL_ALIGN_MIDDLE, $style->getHAlign());
        $this->assertSame(FontStyle::VERTICAL_ALIGN_CENTRAL, $style->getVAlign());
    }
    
    /**
     */
    public function testUpdate()
    {
        $style1 = new FontStyle();
        $style2 = new FontStyle(
            8,
            FontStyle::FONT_COURIER,
            FontStyle::FONT_STYLE_BOLD,
            FontStyle::HORIZONTAL_ALIGN_MIDDLE,
            FontStyle::VERTICAL_ALIGN_CENTRAL
        );
        $style1->update($style2);
    
        $this->assertSame(8, $style1->getSize());
        $this->assertSame(FontStyle::FONT_COURIER, $style1->getName());
        $this->assertSame(FontStyle::FONT_STYLE_BOLD, $style1->getStyle());
        $this->assertSame(FontStyle::HORIZONTAL_ALIGN_MIDDLE, $style1->getHAlign());
        $this->assertSame(FontStyle::VERTICAL_ALIGN_CENTRAL, $style1->getVAlign());
    
        // test independence of style1 and style2
        $style1->setSize(12);
        $this->assertSame(12, $style1->getSize());
        $this->assertSame(8, $style2->getSize());
    }
    
    /**
     */
    public function testVisibility()
    {
        $style = new FontStyle();
        $this->assertTrue($style->isVisible());
        
        $style->setSize(0);
        $this->assertFalse($style->isVisible());
        
        $style->setSize(1);
        $this->assertTrue($style->isVisible());
    }
}
