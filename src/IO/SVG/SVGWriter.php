<?php
namespace VectorGraphics\IO\SVG;

use SimpleXMLElement;
use VectorGraphics\IO\AbstractWriter;
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Graphic\Viewport;
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

        return $this->addShapes(
            $this->createSVG($width, $height, $viewport, $keepRatio),
            $graphic->getElements(),
            $viewport->getYBase()
        )->asXml();
    }

    /**
     * @param SimpleXMLElement $svg
     * @param GraphicElement[] $shapes
     * @param float $yBase
     *
     * @return SimpleXMLElement
     * @throws \Exception
     */
    private function addShapes(SimpleXMLElement $svg, array $shapes, $yBase)
    {
        foreach ($shapes as $shape) {
            if ($shape instanceof Rectangle) {
                $this->addRect($svg, $shape, $yBase);
            } elseif ($shape instanceof Circle) {
                $this->addCircle($svg, $shape, $yBase);
            } elseif ($shape instanceof Shape) {
                $this->addShape($svg, $shape, $yBase);
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
        $d = '';
        foreach ($shape->getPath()->getElements() as $element) {
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
        $path = $svg->addChild("path");
        $path->addAttribute("d", $d);
        $this->addStyle($path, $shape);
        return $path;
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
        $this->addStyle($rect, $rectangle);
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
        $this->addStyle($rect, $circle);
        return $rect;
    }

    /**
     * @param SimpleXMLElement $element
     * @param Shape $shape
     */
    private function addStyle(SimpleXMLElement $element, Shape $shape)
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
            $element->addAttribute("stoke", "none");
        }
        if ($shape->getOpacity() < 1) {
            $element->addAttribute("opacity", $shape->getOpacity());
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
        $svg = new SimpleXMLElement("<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\"></svg>");
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
