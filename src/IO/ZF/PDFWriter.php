<?php
namespace VectorGraphics\IO\ZF;

use InvalidArgumentException;
use VectorGraphics\IO\AbstractWriter;
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Graphic\Viewport;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\LineTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Shape;
use ZendPdf\Color\ColorInterface;
use ZendPdf\Color\Html;
use ZendPdf\InternalType\NumericObject;
use ZendPdf\Page as ZendPage;

class PDFWriter extends AbstractWriter
{
    /** @var PDFWriter */
    private static $instance;

    /**
     * @return PDFWriter
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new PDFWriter();
        }
        return self::$instance;
    }

    /**
     * @param ZendPage $page
     * @param Graphic $graphic
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     * @param bool $keepRatio
     *
     * @throws \Exception
     */
    public function drawGraphic(ZendPage $page, Graphic $graphic, $x, $y, $width, $height, $keepRatio = true)
    {
        $page->saveGS();
        $this->translateToViewport($page, $graphic->getViewport(), $x, $y, $width, $height, $keepRatio);
        foreach ($graphic->getElements() as $element) {
            if ($element instanceof Shape) {
                $this->drawShape($page, $element);
            } else {
                // TODO: cleanup exceptions
                throw new \Exception("Unexpected");
            }
        }
        $page->restoreGS();
    }

    /**
     * @param ZendPage $page
     * @param Viewport $viewport
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     * @param bool $keepRatio
     */
    private function translateToViewport(ZendPage $page, Viewport $viewport, $x, $y, $width, $height, $keepRatio = true)
    {
        $scaleX = $width / $viewport->getWidth();
        $scaleY = $height / $viewport->getHeight();
        $mx = -$viewport->getX();
        $my = -$viewport->getY();
        if ($keepRatio) {
            if ($scaleX < $scaleY) {
                $scaleY = $scaleX;
                // center viewport
                $my += ($height / $scaleY - $viewport->getHeight()) / 2;
            } else {
                $scaleX = $scaleY;
                // center viewport
                $mx += ($width / $scaleX - $viewport->getWidth()) / 2;
            }
        }
        // translate origin to $x/$y
        $page->translate($x, $y);
        // scale to viewport
        $page->scale($scaleX, $scaleY);
        // translate origin to (centered) $viewport origin
        $page->translate($mx, $my);
    }

    /**
     * @param ZendPage $page
     * @param Shape $shape
     *
     * @throws \Exception
     */
    private function drawShape(ZendPage $page, Shape $shape)
    {
        $filled = $shape->getFillColor() !== null && $shape->getFillOpacity() > 0;
        $stroked = $shape->getStrokeColor() !== null && $shape->getStrokeOpacity() > 0 && $shape->getStrokeWidth() > 0;
        if ($shape->getOpacity() === 0 || (!$filled && !$stroked)) {
            // not visible => do nothing
            return;
        }
        $path = $shape->getPath();
        if (!$filled) {
            $this->setLineStyle($page, $shape, $shape->getOpacity());
            $this->drawPath($page, $path, ZendPage::SHAPE_DRAW_STROKE);
        } elseif (!$stroked) {
            $this->setFillStyle($page, $shape, $shape->getOpacity());
            $this->drawPath($page, $path, ZendPage::SHAPE_DRAW_FILL);
        } elseif ($shape->getFillOpacity() !== 1 || $shape->getStrokeOpacity() !== 1) {
            // separate fill and stroke to emulate correct alpha behavior
            $this->setFillStyle($page, $shape, $shape->getOpacity());
            $this->drawPath($page, $path, ZendPage::SHAPE_DRAW_FILL);

            $this->setLineStyle($page, $shape, $shape->getOpacity());
            $this->drawPath($page, $path, ZendPage::SHAPE_DRAW_STROKE);
        } else {
            $this->setLineStyle($page, $shape);
            $this->setFillStyle($page, $shape);
            $page->setAlpha($shape->getOpacity());
            $this->drawPath($page, $path, ZendPage::SHAPE_DRAW_FILL_AND_STROKE);
        }
    }

    /**
     * @param ZendPage $page
     * @param Path $path
     * @param int $fillType
     *
     * @return ZendPage
     * @throws \Exception
     */
    // private function drawPath(ZendPage $page, Path $path, $fillType) // TODO: currently used directly in PHPPdf
    public function drawPath(ZendPage $page, Path $path, $fillType)
    {
        $content = '';
        foreach ($path->getElements() as $element) {
            if ($element instanceof MoveTo) {
                $xObj = new NumericObject($element->getDestX());
                $yObj = new NumericObject($element->getDestY());
                $content .= $xObj->toString() . ' ' . $yObj->toString() . ' ' . " m\n";
            } elseif ($element instanceof LineTo) {
                $xObj = new NumericObject($element->getDestX());
                $yObj = new NumericObject($element->getDestY());
                $content .= $xObj->toString() . ' ' . $yObj->toString() . ' ' . " l\n";
            } elseif ($element instanceof CurveTo) {
                $x1Obj = new NumericObject($element->getControl1X());
                $y1Obj = new NumericObject($element->getControl1Y());
                $x2Obj = new NumericObject($element->getControl2X());
                $y2Obj = new NumericObject($element->getControl2Y());
                $x3Obj = new NumericObject($element->getDestX());
                $y3Obj = new NumericObject($element->getDestY());
                $content .= $x1Obj->toString() . ' ' . $y1Obj->toString() . ' '
                    . $x2Obj->toString() . ' ' . $y2Obj->toString() . ' '
                    . $x3Obj->toString() . ' ' . $y3Obj->toString() . ' '
                    . " c\n";
            } elseif ($element instanceof Close) {
                $content .= "h\n";
            } else {
                // TODO: cleanup exceptions
                throw new \Exception('Unsupported PathElement: ' . get_class($element));
            }
        }


        switch ($fillType) {
            case ZendPage::SHAPE_DRAW_FILL_AND_STROKE:
                $content .= " B*\n";
                break;
            case ZendPage::SHAPE_DRAW_FILL:
                $content .= " f*\n";
                break;
            case ZendPage::SHAPE_DRAW_STROKE:
                $content .= " S\n";
                break;
        }

        return $page->rawWrite($content, 'PDF');
    }

    /**
     * @param ZendPage $page
     * @param Shape $shape
     * @param float|null $opacity
     */
    private function setLineStyle(ZendPage $page, Shape $shape, $opacity = null)
    {
        $page->setLineWidth($shape->getStrokeWidth());
        $page->setLineColor($this->getColor($shape->getStrokeColor()));
        if ($opacity !== null) {
            $page->setAlpha($opacity * $shape->getStrokeOpacity());
        }
    }

    /**
     * @param ZendPage $page
     * @param Shape $shape
     * @param float|null $opacity
     */
    private function setFillStyle(ZendPage $page, Shape $shape, $opacity = null)
    {
        $page->setFillColor($this->getColor($shape->getFillColor()));
        if ($opacity !== null) {
            $page->setAlpha($opacity * $shape->getFillOpacity());
        }
    }

    /**
     * @param $colorData
     *
     * @return ColorInterface
     * @throws InvalidArgumentException
     */
    private function getColor($colorData)
    {
        if(is_string($colorData))
        {
            return Html::color($colorData);
        }

        if(!$colorData instanceof ColorInterface)
        {
            throw new InvalidArgumentException('Wrong color value, expected string or object of '.ColorInterface::class.' class.');
        }

        return $colorData;
    }
}
