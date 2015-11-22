<?php
namespace VectorGraphics\IO\SVG;

use SimpleXMLElement;
use VectorGraphics\IO\AbstractWriter;
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Graphic\AbstractText;
use VectorGraphics\Model\Graphic\Text;
use VectorGraphics\Model\Graphic\PathText;
use VectorGraphics\Model\Graphic\Viewport;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\LineTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Shape;
use VectorGraphics\Model\Shape\Circle;
use VectorGraphics\Model\Shape\Rectangle;

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
            if ($element instanceof Text) {
                $this->addText($svg, $element, $yBase);
            } elseif ($element instanceof PathText) {
                $this->addPathText($svg, $element, $yBase);
            } elseif ($element instanceof Rectangle) {
                $this->addRect($svg, $element, $yBase);
            } elseif ($element instanceof Circle) {
                $this->addCircle($svg, $element, $yBase);
            } elseif ($element instanceof Shape) {
                $this->addShape($svg, $element, $yBase);
            } else {
                // TODO: cleanup exceptions
                throw new \Exception("Unexpected");
            }
        }
        return $svg;
    }
    
    /**
     * @param SimpleXMLElement $svg
     * @param Shape $shape
     * @param float $yBase
     *
     * @return SimpleXMLElement
     * @throws \Exception
     */
    private function addShape(SimpleXMLElement $svg, Shape $shape, $yBase)
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
        switch ($element->getHAlign()) {
            case AbstractText::HORIZONTAL_ALIGN_MIDDLE:
                $textPath->addAttribute('startOffset', 0.5);
                break;
            case AbstractText::HORIZONTAL_ALIGN_RIGHT:
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
     * @param Shape\Circle $circle
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
                // TODO: cleanup exceptions
                throw new \Exception("Unexpected");
            }
        }
        $child = $svg->addChild("path");
        $child->addAttribute("d", $d);
        return $child;
    }
    
    /**
     * @param SimpleXMLElement $element
     * @param Shape $shape
     */
    private function addShapeStyle(SimpleXMLElement $element, Shape $shape)
    {
        if ($shape->getFillColor() !== null && $shape->getFillOpacity() > 0) {
            $element->addAttribute("fill", $shape->getFillColor());
            $element->addAttribute("fill-opacity", $shape->getFillOpacity());
            $element->addAttribute("fill-rule", "evenodd");
        } else {
            $element->addAttribute("fill", "none");
        }
        if ($shape->getStrokeColor() !== null && $shape->getStrokeOpacity() > 0 && $shape->getStrokeWidth() > 0) {
            $element->addAttribute("stroke", $shape->getStrokeColor());
            $element->addAttribute("stroke-opacity", $shape->getStrokeOpacity());
            $element->addAttribute("stroke-width", $shape->getStrokeWidth());
        } else {
            $element->addAttribute("stroke", "none");
        }
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
        $element->addAttribute("font-family", $text->getFontName() . ', sans-serif');
        $element->addAttribute("font-size", $text->getFontSize());
        switch ($text->getFontStyle()) {
            case AbstractText::FONT_STYLE_BOLD:
                $element->addAttribute("font-weight", 'bold');
                break;
            case AbstractText::FONT_STYLE_ITALIC:
                $element->addAttribute("font-style", 'italic');
                break;
            case AbstractText::FONT_STYLE_BOLD_ITALIC:
                $element->addAttribute("font-weight", 'bold');
                $element->addAttribute("font-style", 'italic');
                break;
        }
        switch ($text->getHAlign()) {
            case AbstractText::HORIZONTAL_ALIGN_LEFT:
                $element->addAttribute("text-anchor", "start");
                break;
            case AbstractText::HORIZONTAL_ALIGN_MIDDLE:
                $element->addAttribute("text-anchor", "middle");
                break;
            case AbstractText::HORIZONTAL_ALIGN_RIGHT:
                $element->addAttribute("text-anchor", "end");
                break;
        }
        switch ($text->getVAlign()) {
            case AbstractText::VERTICAL_ALIGN_TOP:
                $element->addAttribute("alignment-baseline", "text-before-edge");
                break;
            case AbstractText::VERTICAL_ALIGN_CENTRAL:
                $element->addAttribute("alignment-baseline", "central");
                break;
            case AbstractText::VERTICAL_ALIGN_BASE:
                $element->addAttribute("alignment-baseline", "alphabetic");
                break;
            case AbstractText::VERTICAL_ALIGN_BOTTOM:
                $element->addAttribute("alignment-baseline", "text-after-edge");
                break;
        }
        
        if ($text->getFillColor() !== null && $text->getFillOpacity() > 0) {
            $element->addAttribute("fill", $text->getFillColor());
            $element->addAttribute("fill-opacity", $text->getFillOpacity());
            $element->addAttribute("fill-rule", "evenodd");
        } else {
            $element->addAttribute("fill", "none");
        }
        if ($text->getStrokeColor() !== null && $text->getStrokeOpacity() > 0 && $text->getStrokeWidth() > 0) {
            $element->addAttribute("stroke", $text->getStrokeColor());
            $element->addAttribute("stroke-opacity", $text->getStrokeOpacity());
            $element->addAttribute("stroke-width", $text->getStrokeWidth());
        } else {
            $element->addAttribute("stroke", "none");
        }
        if ($text->getOpacity() < 1) {
            $element->addAttribute("opacity", $text->getOpacity());
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
