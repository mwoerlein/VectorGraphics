<?php
namespace VectorGraphics\Model\Graphic;

use VectorGraphics\Model\Path;
use VectorGraphics\Model\Shape;
use VectorGraphics\Model\Shape\Circle;
use VectorGraphics\Model\Shape\PathShape;
use VectorGraphics\Model\Shape\Rectangle;
use VectorGraphics\Model\Shape\RingArc;
use VectorGraphics\Model\Style\FillStyle;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\StrokeStyle;

abstract class AbstractElementContainer
{
    /** @var GraphicElement[] */
    private $elements = [];
    
    /** @var FillStyle */
    private $defaultShapeFillStyle;
    
    /** @var StrokeStyle */
    private $defaultShapeStrokeStyle;
    
    /** @var FillStyle */
    private $defaultTextFillStyle;
    
    /** @var StrokeStyle */
    private $defaultTextStrokeStyle;
    
    /** @var FontStyle */
    private $defaultTextFontStyle;
    
    public function __construct()
    {
        $this->defaultShapeFillStyle = new FillStyle(); // no fill
        $this->defaultShapeStrokeStyle = new StrokeStyle(1, "black"); // black stroke
        $this->defaultTextFillStyle = new FillStyle("black"); // black fill
        $this->defaultTextStrokeStyle = new StrokeStyle(); // no stroke
        $this->defaultTextFontStyle = new FontStyle(); // 12 point Times, left aligned
    }
    
    /**
     * @return GraphicElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }
    
    /**
     * @return FillStyle
     */
    public function getDefaultShapeFillStyle()
    {
        return $this->defaultShapeFillStyle;
    }
    
    /**
     * @return StrokeStyle
     */
    public function getDefaultShapeStrokeStyle()
    {
        return $this->defaultShapeStrokeStyle;
    }
    
    /**
     * @return FillStyle
     */
    public function getDefaultTextFillStyle()
    {
        return $this->defaultTextFillStyle;
    }
    
    /**
     * @return StrokeStyle
     */
    public function getDefaultTextStrokeStyle()
    {
        return $this->defaultTextStrokeStyle;
    }
    
    /**
     * @return FontStyle
     */
    public function getDefaultTextFontStyle()
    {
        return $this->defaultTextFontStyle;
    }
    
    /**
     * @param GraphicElement $element
     *
     * @return $this
     */
    public function add(GraphicElement $element) {
        $this->elements[] = $element;
        return $this;
    }
    
    /**
     * @param string $text
     * @param float $x
     * @param float $y
     *
     * @return Text
     */
    public function addText($text, $x, $y) {
        return $this->addAndInitText(new Text($text, $x, $y));
    }
    
    /**
     * @param string $text
     * @param Path $path
     *
     * @return Text
     */
    public function addPathText($text, Path $path) {
        return $this->addAndInitText(new PathText($text, $path));
    }
    
    /**
     * @param float $x
     * @param float $y
     * @param float $radius
     *
     * @return Circle
     */
    public function addCircle($x, $y, $radius) {
        return $this->addAndInitShape(new Circle($x, $y, $radius));
    }
    
    /**
     * @param Path $path
     *
     * @return PathShape
     */
    public function addPath(Path $path)
    {
        return $this->addAndInitShape(new PathShape($path));
    }
    
    /**
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     * 
     * @return Rectangle
     */
    public function addRectangle($x, $y, $width, $height)
    {
        return $this->addAndInitShape(new Rectangle($x, $y, $width, $height));
    }
    
    /**
     * @param float $x
     * @param float $y
     * @param float $innerRadius
     * @param float $outerRadius
     * @param float $alpha
     * @param float $angle
     *
     * @return RingArc
     */
    public function addRingArc($x, $y, $innerRadius, $outerRadius, $alpha = 0., $angle = 360.)
    {
        return $this->addAndInitShape(new RingArc($x, $y, $innerRadius, $outerRadius, $alpha, $angle));
    }
    
    /**
     * @param Shape $shape
     *
     * @return Shape
     */
    private function addAndInitShape(Shape $shape) {
        $shape->getFillStyle()->update($this->defaultShapeFillStyle);
        $shape->getStrokeStyle()->update($this->defaultShapeStrokeStyle);
        $this->add($shape);
        return $shape;
    }
    
    /**
     * @param AbstractText $text
     *
     * @return AbstractText
     */
    private function addAndInitText(AbstractText $text) {
        $text->getFillStyle()->update($this->defaultTextFillStyle);
        $text->getStrokeStyle()->update($this->defaultTextStrokeStyle);
        $text->getFontStyle()->update($this->defaultTextFontStyle);
        $this->add($text);
        return $text;
    }
}
