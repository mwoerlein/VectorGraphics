<?php
namespace VectorGraphics\Tests\Model\Text;

use VectorGraphics\Model\Style\FillStyle;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\StrokeStyle;
use VectorGraphics\Model\Text\Text;

/**
 * @covers VectorGraphics\Model\Text\Text
 * 
 * @covers VectorGraphics\Model\Graphic\GraphicElement
 * @covers VectorGraphics\Model\Text\AbstractText
 * @covers VectorGraphics\Model\Style\FillStyledTrait
 * @covers VectorGraphics\Model\Style\FontStyledTrait
 * @covers VectorGraphics\Model\Style\StrokeStyledTrait
 */
class TextTest extends AbstractTextTest
{
    /**
     * @param string $text
     * @param float $x
     * @param float $y
     *
     * @return Text
     */
    protected function createText($text = 'text', $x = 1., $y = 2.)
    {
        return new Text($text, $x, $y);
    }
    
    /**
     */
    public function testGetter()
    {
        $text = $this->createText('My Text', 2, 3);
        $this->assertSame('My Text', $text->getText());
        $this->assertSame(2., $text->getX());
        $this->assertSame(3., $text->getY());
        $this->assertSame(0., $text->getRotation());
        $this->assertSame(1., $text->getOpacity());
        $this->assertTrue($text->isVisible());
        $this->assertInstanceOf(FillStyle::class, $text->getFillStyle());
        $this->assertInstanceOf(FontStyle::class, $text->getFontStyle());
        $this->assertInstanceOf(StrokeStyle::class, $text->getStrokeStyle());
    }
    
    /**
     */
    public function testRotation()
    {
        $text = $this->createText();
        $text->setRotation(1);
        $this->assertSame(1., $text->getRotation());
        
        $text->setRotation(-12.2);
        $this->assertSame(347.8, $text->getRotation());
        
        $text->setRotation(367);
        $this->assertSame(7., $text->getRotation());
    }
}
