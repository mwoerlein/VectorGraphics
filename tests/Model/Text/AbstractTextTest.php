<?php
namespace VectorGraphics\Tests\Model\Text;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Text\AbstractText;

/**
 * @covers VectorGraphics\Model\Graphic\GraphicElement
 * @covers VectorGraphics\Model\Text\AbstractText
 * @covers VectorGraphics\Model\Style\FillStyledTrait
 * @covers VectorGraphics\Model\Style\FontStyledTrait
 * @covers VectorGraphics\Model\Style\StrokeStyledTrait
 */
abstract class AbstractTextTest extends TestCase
{
    /**
     * @param string $text
     *
     * @return AbstractText
     */
    abstract protected function createText($text = 'text');
    
    /**
     * @return array[]
     */
    public function visibilityProvider()
    {
        $data = [];
        $data['no text'] = [
            'text' => '',
            'modify' => [],
            'visible' => false,
        ];
        
        $data['opaque, but visible'] = [
            'text' => 'text',
            'modify' => [
                'opacity' => [0.1]
            ],
            'visible' => true,
        ];
        $data['opaque, not visible'] = [
            'text' => 'text',
            'modify' => [
                'opacity' => [0]
            ],
            'visible' => false,
        ];
        
        $data['no color'] = [
            'text' => 'text',
            'modify' => [
                'fillColor' => [null],
                'strokeColor' => [null],
            ],
            'visible' => false,
        ];
        $data['just stroke'] = [
            'text' => 'text',
            'modify' => [
                'fillColor' => [null],
                'strokeColor' => ['black'],
            ],
            'visible' => true,
        ];
        $data['just fill'] = [
            'text' => 'text',
            'modify' => [
                'fillColor' => ['black'],
                'strokeColor' => [null],
            ],
            'visible' => true,
        ];
        $data['opaque colors'] = [
            'text' => 'text',
            'modify' => [
                'fillColor' => ['black', 0],
                'strokeColor' => ['black', 0],
            ],
            'visible' => false,
        ];
        $data['opaque colors, but visible'] = [
            'text' => 'text',
            'modify' => [
                'fillOpacity' => [0.1],
                'strokeOpacity' => [0.1],
            ],
            'visible' => true,
        ];
        
        $data['no fill, no stroke'] = [
            'text' => 'text',
            'modify' => [
                'fillColor' => [null],
                'strokeColor' => ['black'],
                'strokeWidth' => [0],
            ],
            'visible' => false,
        ];
        
        $data['no size'] = [
            'text' => 'text',
            'modify' => [
                'font' => [0],
            ],
            'visible' => false,
        ];
        return $data;
    }
    
    /**
     * @param string $text
     * @param array[] $modify
     * @param bool $visible
     *
     * @dataProvider visibilityProvider
     */
    public function testVisibility($text, array $modify, $visible)
    {
        $text = $this->createText($text);
        foreach ($modify as $type => $args) {
            call_user_func_array([$text, 'set' . ucfirst($type)], $args);
        }
        $this->assertSame($visible, $text->isVisible());
    }
    
    /*
     */
    public function testFillSetter()
    {
        $text = $this->createText();
        $fillStyle = $text->getFillStyle();
        // default color for text: 'black'
        $this->assertSame('black', $fillStyle->getColor()->__toString());
        $this->assertSame(1., $fillStyle->getOpacity());
        
        $text->setFillColor('green', 0.8);
        $this->assertSame('green', $fillStyle->getColor()->__toString());
        $this->assertSame(0.8, $fillStyle->getOpacity());
        
        $text->setFillOpacity(0.5);
        $this->assertSame('green', $fillStyle->getColor()->__toString());
        $this->assertSame(0.5, $fillStyle->getOpacity());
        
        $text->setFillColor('red');
        $this->assertSame('red', $fillStyle->getColor()->__toString());
        $this->assertSame(1., $fillStyle->getOpacity());
    }
    
    /*
     */
    public function testStrokeSetter()
    {
        $text = $this->createText();
        $strokeStyle = $text->getStrokeStyle();
        // default color for text: none
        $this->assertNull($strokeStyle->getColor());
        $this->assertSame(0., $strokeStyle->getOpacity());
        
        $text->setStrokeColor('green', 0.8);
        $this->assertNotNull($strokeStyle->getColor());
        $this->assertSame('green', $strokeStyle->getColor()->__toString());
        $this->assertSame(0.8, $strokeStyle->getOpacity());
        
        $text->setStrokeOpacity(0.5);
        $this->assertNotNull($strokeStyle->getColor());
        $this->assertSame('green', $strokeStyle->getColor()->__toString());
        $this->assertSame(0.5, $strokeStyle->getOpacity());
        
        $text->setStrokeColor('red');
        $this->assertNotNull($strokeStyle->getColor());
        $this->assertSame('red', $strokeStyle->getColor()->__toString());
        
        // default width for text: 1.
        $this->assertSame(1., $strokeStyle->getWidth());
        
        $text->setStrokeWidth(15.9);
        $this->assertSame(15.9, $strokeStyle->getWidth());
    }
    
    public function testFontSetter()
    {
        $text = $this->createText();
        $fontStyle = $text->getFontStyle();
        
        // default font for text: 12 point Times, normal
        $this->assertSame(12, $fontStyle->getSize());
        $this->assertSame(FontStyle::FONT_TIMES, $fontStyle->getName());
        $this->assertSame(FontStyle::FONT_STYLE_NORMAL, $fontStyle->getStyle());
        
        $text->setFont(10, FontStyle::FONT_HELVETICA, FontStyle::FONT_STYLE_BOLD_ITALIC);
        $this->assertSame(10, $fontStyle->getSize());
        $this->assertSame(FontStyle::FONT_HELVETICA, $fontStyle->getName());
        $this->assertSame(FontStyle::FONT_STYLE_BOLD_ITALIC, $fontStyle->getStyle());
        
        $text->setFont(8);
        $this->assertSame(8, $fontStyle->getSize());
        $this->assertSame(FontStyle::FONT_TIMES, $fontStyle->getName());
        $this->assertSame(FontStyle::FONT_STYLE_NORMAL, $fontStyle->getStyle());
        
        // default alignment for text: left, baseline
        $this->assertSame(FontStyle::HORIZONTAL_ALIGN_LEFT, $fontStyle->getHAlign());
        $this->assertSame(FontStyle::VERTICAL_ALIGN_BASE, $fontStyle->getVAlign());
        
        $text->align(FontStyle::HORIZONTAL_ALIGN_MIDDLE, FontStyle::VERTICAL_ALIGN_CENTRAL);
        $this->assertSame(FontStyle::HORIZONTAL_ALIGN_MIDDLE, $fontStyle->getHAlign());
        $this->assertSame(FontStyle::VERTICAL_ALIGN_CENTRAL, $fontStyle->getVAlign());
        
        $text->align(FontStyle::HORIZONTAL_ALIGN_RIGHT);
        $this->assertSame(FontStyle::HORIZONTAL_ALIGN_RIGHT, $fontStyle->getHAlign());
        $this->assertSame(FontStyle::VERTICAL_ALIGN_BASE, $fontStyle->getVAlign());
    }
}
