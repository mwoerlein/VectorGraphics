<?php
namespace VectorGraphics\Tests\Model\Text;

use VectorGraphics\Model\Path;
use VectorGraphics\Model\Style\FillStyle;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\StrokeStyle;
use VectorGraphics\Model\Text\PathText;

/**
 * @covers VectorGraphics\Model\Text\PathText
 *
 * @covers VectorGraphics\Model\Graphic\GraphicElement
 * @covers VectorGraphics\Model\Text\AbstractText
 * @covers VectorGraphics\Model\Style\FillStyledTrait
 * @covers VectorGraphics\Model\Style\FontStyledTrait
 * @covers VectorGraphics\Model\Style\StrokeStyledTrait
 */
class PathTextTest extends AbstractTextTest
{
    /**
     * @param string $text
     * @param Path|null $path
     *
     * @return PathText
     */
    protected function createText($text = 'text', Path $path = null)
    {
        return new PathText($text, $path ?: new Path(0, 0));
    }
    
    /**
     */
    public function testGetter()
    {
        $path = new Path(0, 0);
        $text = $this->createText('My Text', $path);
        $this->assertSame('My Text', $text->getText());
        $this->assertSame($path, $text->getPath());
        $this->assertTrue($text->isVisible());
        $this->assertInstanceOf(FillStyle::class, $text->getFillStyle());
        $this->assertInstanceOf(FontStyle::class, $text->getFontStyle());
        $this->assertInstanceOf(StrokeStyle::class, $text->getStrokeStyle());
    }
}
