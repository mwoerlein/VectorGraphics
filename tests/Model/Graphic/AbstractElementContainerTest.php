<?php
namespace VectorGraphics\Tests\Model\Graphic;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Graphic\AbstractElementContainer;
use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Shape\Circle;
use VectorGraphics\Model\Shape\PathShape;
use VectorGraphics\Model\Shape\Rectangle;
use VectorGraphics\Model\Shape\RingArc;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Text\PathText;
use VectorGraphics\Model\Text\Text;

/**
 * @covers VectorGraphics\Model\Graphic\AbstractElementContainer
 */
abstract class AbstractElementContainerTest extends TestCase
{
    /**
     * @return AbstractElementContainer
     */
    abstract protected function createContainer();
    
    public function testStyleDefaults()
    {
        $container = $this->createContainer();
        
        $defaultShapeStrokeStyle = $container->getDefaultShapeStrokeStyle();
        $this->assertEquals(1., $defaultShapeStrokeStyle->getWidth());
        $this->assertEquals('black', $defaultShapeStrokeStyle->getColor()->__toString());
        $this->assertEquals(1., $defaultShapeStrokeStyle->getOpacity());
        
        $defaultShapeFillStyle = $container->getDefaultShapeFillStyle();
        $this->assertNull($defaultShapeFillStyle->getColor());
        $this->assertEquals(0., $defaultShapeFillStyle->getOpacity());
    
        $defaultTextStrokeStyle = $container->getDefaultTextStrokeStyle();
        $this->assertEquals(1., $defaultTextStrokeStyle->getWidth());
        $this->assertNull($defaultTextStrokeStyle->getColor());
        $this->assertEquals(0., $defaultTextStrokeStyle->getOpacity());
    
        $defaultTextFillStyle = $container->getDefaultTextFillStyle();
        $this->assertEquals('black', $defaultTextFillStyle->getColor()->__toString());
        $this->assertEquals(1., $defaultTextFillStyle->getOpacity());
        
        $defaultTextFontStyle = $container->getDefaultTextFontStyle();
        $this->assertEquals(12, $defaultTextFontStyle->getSize());
        $this->assertEquals(FontStyle::FONT_TIMES, $defaultTextFontStyle->getName());
        $this->assertEquals(FontStyle::FONT_STYLE_NORMAL, $defaultTextFontStyle->getStyle());
        $this->assertEquals(FontStyle::HORIZONTAL_ALIGN_LEFT, $defaultTextFontStyle->getHAlign());
        $this->assertEquals(FontStyle::VERTICAL_ALIGN_BASE, $defaultTextFontStyle->getVAlign());
    }
    
    public function elementsProvider()
    {
        $data = [];
        $data['empty'] = [
            'add' => [],
            'elements' => [],
        ];
        $data['add circle'] = [
            'add' => [
                "circle" => [1, 2, 3]
            ],
            'elements' => [
                new Circle(1, 2, 3),
            ],
        ];
        $data['add rectangle'] = [
            'add' => [
                "rectangle" => [1, 2, 3, 4]
            ],
            'elements' => [
                new Rectangle(1, 2, 3, 4),
            ],
        ];
        $data['add ring arc'] = [
            'add' => [
                "ringArc" => [1, 2, 3, 4, 5, 6]
            ],
            'elements' => [
                new RingArc(1, 2, 3, 4, 5, 6),
            ],
        ];
        $path = new Path();
        $data['add path'] = [
            'add' => [
                "path" => [$path]
            ],
            'elements' => [
                new PathShape($path),
            ],
        ];
        
        $data['add texts'] = [
            'add' => [
                'text' => ['my text', 1, 2],
                'pathText' => ['path text', $path],
            ],
            'elements' => [
                new Text('my text', 1, 2),
                new PathText('path text', $path),
            ],
        ];
        return $data;
    }
    
    /**
     * @param array[] $add
     * @param GraphicElement[] $elements
     * 
     * @dataProvider elementsProvider
     */
    public function testGetAndAddElements(array $add, array $elements)
    {
        $container = $this->createContainer();
        foreach ($add as $type => $arguments) {
            call_user_func_array([$container, 'add' . ucfirst($type)], $arguments);
        }
        $this->assertEquals($elements, $container->getElements());
    }
    
    public function testApplyDefaults()
    {
        $container = $this->createContainer();
        $circle1 = $container->addCircle(1, 2, 3);
        $this->assertNull($circle1->getFillStyle()->getColor());
            
        $container->getDefaultShapeFillStyle()->setColor('red', 0.5);
        $container->getDefaultShapeStrokeStyle()->setColor('yellow', 0.8);
        $circle2 = $container->addCircle(1, 2, 3);
        $this->assertSame('red', $circle2->getFillStyle()->getColor()->__toString());
        $this->assertSame(0.5, $circle2->getFillStyle()->getOpacity());
        $this->assertSame('yellow', $circle2->getStrokeStyle()->getColor()->__toString());
        $this->assertSame(0.8, $circle2->getStrokeStyle()->getOpacity());
        
        // ensure default-changes apply just to new shapes!
        $this->assertNull($circle1->getFillStyle()->getColor());
    
        $text1 = $container->addText('my text', 1, 2);
        $this->assertSame('black', $text1->getFillStyle()->getColor()->__toString());
        
        $container->getDefaultTextFillStyle()->setColor('red', 0.5);
        $container->getDefaultTextStrokeStyle()->setColor('yellow', 0.8);
        $container->getDefaultTextFontStyle()->setSize(8)->setName(FontStyle::FONT_HELVETICA);
        $text2 = $container->addText('my text', 1, 2);
        $this->assertSame('red', $text2->getFillStyle()->getColor()->__toString());
        $this->assertSame(0.5, $text2->getFillStyle()->getOpacity());
        $this->assertSame('yellow', $text2->getStrokeStyle()->getColor()->__toString());
        $this->assertSame(0.8, $text2->getStrokeStyle()->getOpacity());
        $this->assertSame(8, $text2->getFontStyle()->getSize());
        $this->assertSame(FontStyle::FONT_HELVETICA, $text2->getFontStyle()->getName());
        
        // ensure default-changes apply just to new texts!
        $this->assertSame('black', $text1->getFillStyle()->getColor()->__toString());
    }
}
