<?php
namespace VectorGraphics\IO\SVG;

use InvalidArgumentException;
use SimpleXMLElement;
use VectorGraphics\IO\AbstractWriter;
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Graphic\Viewport;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\LineTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Shape\AbstractShape;
use VectorGraphics\Model\Shape\Circle;
use VectorGraphics\Model\Shape\Rectangle;
use VectorGraphics\Model\Style\FillStyle;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\StrokeStyle;
use VectorGraphics\Model\Text\AbstractText;
use VectorGraphics\Model\Text\PathText;
use VectorGraphics\Model\Text\Text;

class SVGWriter extends AbstractWriter
{
    /**
     * @param Graphic $graphic
     * @param float $width in cm
     * @param float $height in cm
     * @param bool $keepRatio
     *
     * @return mixed
     */
    public function toSVG(Graphic $graphic, $width = 10., $height = 10., $keepRatio = true) {
        $viewport = $graphic->getViewport();
        
        return $this->addElements(
            $this->createSVG($width, $height, $viewport, $keepRatio),
            $graphic->getElements(),
            $viewport->getYBase()
        )->asXml();
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param GraphicElement[] $elements
     * @param float $yBase
     *
     * @return SimpleXMLElement
     * @throws \Exception
     */
    private function addElements(SimpleXMLElement $svg, array $elements, $yBase)
    {
        foreach ($elements as $element) {
            if (!$element->isVisible()) {
                continue;
            }
            if ($element instanceof Text) {
                $this->addText($svg, $element, $yBase);
            } elseif ($element instanceof PathText) {
                $this->addPathText($svg, $element, $yBase);
            } elseif ($element instanceof Rectangle) {
                $this->addRect($svg, $element, $yBase);
            } elseif ($element instanceof Circle) {
                $this->addCircle($svg, $element, $yBase);
            } elseif ($element instanceof AbstractShape) {
                $this->addShape($svg, $element, $yBase);
            } else {
                throw new InvalidArgumentException('unsupported graphic element: ' . get_class($element));
            }
        }
        return $svg;
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param AbstractShape $shape
     * @param float $yBase
     *
     * @return SimpleXMLElement
     * @throws \Exception
     */
    private function addShape(SimpleXMLElement $svg, AbstractShape $shape, $yBase)
    {
        $path = $this->addPath($svg, $shape->getPath(), $yBase);
        $this->addShapeStyle($path, $shape);
        return $path;
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param Text $element
     * @param float $yBase
     *
     * @return SimpleXMLElement
     */
    private function addText(SimpleXMLElement $svg, Text $element, $yBase)
    {
        $x = $element->getX();
        $y = $yBase - $element->getY();
        
        if ($element->getRotation() > 0) {
            $group = $svg->addChild('g');
            $group->addAttribute("transform", "translate($x, $y) rotate({$element->getRotation()})");
            $text = $group->addChild("text", $element->getText());
            $text->addAttribute("x", 0);
            $text->addAttribute("y", 0);
        } else {
            $text = $svg->addChild("text", $element->getText());
            $text->addAttribute("x", $x);
            $text->addAttribute("y", $y);
        }
        
        $this->addTextStyle($text, $element);
        return $text;
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param PathText $element
     * @param float $yBase
     *
     * @return SimpleXMLElement
     */
    private function addPathText(SimpleXMLElement $svg, PathText $element, $yBase)
    {
        $pathID = spl_object_hash($element);
        
        $group = $svg->addChild('g');
        $path = $this->addPath($group->addChild("defs"), $element->getPath(), $yBase);
        $path->addAttribute('id', $pathID);
        
        $textPath = $group->addChild("text")->addChild("textPath");
        $textPath->addAttribute('xmlns:xlink:href', "#$pathID");
        switch ($element->getFontStyle()->getHAlign()) {
            case FontStyle::HORIZONTAL_ALIGN_MIDDLE:
                $textPath->addAttribute('startOffset', 0.5);
                break;
            case FontStyle::HORIZONTAL_ALIGN_RIGHT:
                $textPath->addAttribute('startOffset', 1);
                break;
        }
        
        $tspan = $textPath->addChild("tspan", $element->getText());
        $this->addTextStyle($tspan, $element);
        return $tspan;
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param Rectangle $rectangle
     * @param float $yBase
     *
     * @return SimpleXMLElement
     */
    private function addRect(SimpleXMLElement $svg, Rectangle $rectangle, $yBase)
    {
        $rect = $svg->addChild("rect");
        $rect->addAttribute("x", $rectangle->getX());
        $rect->addAttribute("y", $yBase - $rectangle->getY()-$rectangle->getHeight());
        $rect->addAttribute("width", $rectangle->getWidth());
        $rect->addAttribute("height", $rectangle->getHeight());
        $this->addShapeStyle($rect, $rectangle);
        return $rect;
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param Circle $circle
     * @param float $yBase
     *
     * @return SimpleXMLElement
     */
    private function addCircle(SimpleXMLElement $svg, Circle $circle, $yBase)
    {
        $rect = $svg->addChild("circle");
        $rect->addAttribute("cx", $circle->getX());
        $rect->addAttribute("cy", $yBase - $circle->getY());
        $rect->addAttribute("r", $circle->getRadius());
        $this->addShapeStyle($rect, $circle);
        return $rect;
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param Path $path
     * @param $yBase
     *
     * @return SimpleXMLElement
     * @throws \Exception
     */
    private function addPath(SimpleXMLElement $svg, Path $path, $yBase)
    {
        $d = '';
        foreach ($path->getElements() as $element) {
            if ($element instanceof MoveTo) {
                $d .= 'M ' . $element->getDestX() . ',' . ($yBase - $element->getDestY()) . ' ';
            } elseif ($element instanceof LineTo) {
                $d .= 'L ' . $element->getDestX() . ',' . ($yBase - $element->getDestY()) . ' ';
            } elseif ($element instanceof CurveTo) {
                $d .= 'C '
                    . $element->getControl1X() . ',' . ($yBase - $element->getControl1Y()) . ' '
                    . $element->getControl2X() . ',' . ($yBase - $element->getControl2Y()) . ' '
                    . $element->getDestX() . ',' . ($yBase - $element->getDestY()) . ' ';
            } elseif ($element instanceof Close) {
                $d .= "Z ";
            } else {
                throw new InvalidArgumentException('unsupported path element: ' . get_class($element));
            }
        }
        $child = $svg->addChild("path");
        $child->addAttribute("d", $d);
        return $child;
    }
    
    /**
     * @param SimpleXMLElement $element
     * @param AbstractShape $shape
     */
    private function addShapeStyle(SimpleXMLElement $element, AbstractShape $shape)
    {
        $this->addFillStyle($element, $shape->getFillStyle());
        $this->addStrokeStyle($element, $shape->getStrokeStyle());
        if ($shape->getOpacity() < 1) {
            $element->addAttribute("opacity", $shape->getOpacity());
        }
    }
    /**
     * @param SimpleXMLElement $element
     * @param AbstractText $text
     */
    private function addTextStyle(SimpleXMLElement $element, AbstractText $text)
    {
        $this->addFontStyle($element, $text->getFontStyle());
        $this->addFillStyle($element, $text->getFillStyle());
        $this->addStrokeStyle($element, $text->getStrokeStyle());
        if ($text->getOpacity() < 1) {
            $element->addAttribute("opacity", $text->getOpacity());
        }
        
    }
    
    /**
     * @param SimpleXMLElement $element
     * @param FillStyle $fillStyle
     */
    private function addFillStyle(SimpleXMLElement $element, FillStyle $fillStyle)
    {
        if ($fillStyle->isVisible()) {
            $element->addAttribute("fill", $fillStyle->getColor());
            $element->addAttribute("fill-opacity", $fillStyle->getOpacity());
            $element->addAttribute("fill-rule", "evenodd");
        } else {
            $element->addAttribute("fill", "none");
        }
    }
    
    /**
     * @param SimpleXMLElement $element
     * @param StrokeStyle $strokeStyle
     */
    private function addStrokeStyle(SimpleXMLElement $element, StrokeStyle $strokeStyle)
    {
        if ($strokeStyle->isVisible()) {
            $element->addAttribute("stroke", $strokeStyle->getColor());
            $element->addAttribute("stroke-opacity", $strokeStyle->getOpacity());
            $element->addAttribute("stroke-width", $strokeStyle->getWidth());
        } else {
            $element->addAttribute("stroke", "none");
        }
    }
    
    /**
     * @param SimpleXMLElement $element
     * @param FontStyle $fontStyle
     */
    private function addFontStyle(SimpleXMLElement $element, FontStyle $fontStyle)
    {
        $element->addAttribute("font-family", $fontStyle->getName() . ', sans-serif');
        $element->addAttribute("font-size", $fontStyle->getSize());
        switch ($fontStyle->getStyle()) {
            case FontStyle::FONT_STYLE_BOLD:
                $element->addAttribute("font-weight", 'bold');
                break;
            case FontStyle::FONT_STYLE_ITALIC:
                $element->addAttribute("font-style", 'italic');
                break;
            case FontStyle::FONT_STYLE_BOLD_ITALIC:
                $element->addAttribute("font-weight", 'bold');
                $element->addAttribute("font-style", 'italic');
                break;
        }
        switch ($fontStyle->getHAlign()) {
            case FontStyle::HORIZONTAL_ALIGN_LEFT:
                $element->addAttribute("text-anchor", "start");
                break;
            case FontStyle::HORIZONTAL_ALIGN_MIDDLE:
                $element->addAttribute("text-anchor", "middle");
                break;
            case FontStyle::HORIZONTAL_ALIGN_RIGHT:
                $element->addAttribute("text-anchor", "end");
                break;
        }
        switch ($fontStyle->getVAlign()) {
            case FontStyle::VERTICAL_ALIGN_TOP:
                $element->addAttribute("alignment-baseline", "text-before-edge");
                break;
            case FontStyle::VERTICAL_ALIGN_CENTRAL:
                $element->addAttribute("alignment-baseline", "central");
                break;
            case FontStyle::VERTICAL_ALIGN_BASE:
                $element->addAttribute("alignment-baseline", "alphabetic");
                break;
            case FontStyle::VERTICAL_ALIGN_BOTTOM:
                $element->addAttribute("alignment-baseline", "text-after-edge");
                break;
        }
    }
    
    /**
     * @param float $width
     * @param float $height
     * @param Viewport $viewport
     * @param bool $keepRation
     *
     * @return SimpleXMLElement
     * @internal param Graphic $graphic
     *
     */
    private function createSVG($width, $height, Viewport $viewport, $keepRation)
    {
        $svg = new SimpleXMLElement("<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"></svg>");
        $svg->addAttribute("width", $width . "cm");
        $svg->addAttribute("height", $height . "cm");
        
        $svg->addAttribute(
            "viewBox",
            sprintf("%f %f %f %f", $viewport->getX(), $viewport->getY(), $viewport->getWidth(), $viewport->getHeight())
        );
        if (!$keepRation) {
            $svg->addAttribute("preserveAspectRatio", "none");
        }
        return $svg;
    }
}
