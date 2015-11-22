<?php
namespace VectorGraphics\IO\ZF;

use InvalidArgumentException;
use VectorGraphics\IO\AbstractWriter;
use VectorGraphics\Model\Graphic;
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
use VectorGraphics\Utils\TextUtils;
use ZendPdf\Color\ColorInterface as ZendColor;
use ZendPdf\Color\Html as ZendHtmlColor;
use ZendPdf\Font as ZendFont;
use ZendPdf\InternalType\NumericObject as ZendNumericObject;
use ZendPdf\InternalType\StringObject as ZendStringObject;
use ZendPdf\Page as ZendPage;
use ZendPdf\Resource\Font\AbstractFont as ZendAbstractFont;

class PDFWriter extends AbstractWriter
{
    /** Stroke the text only. Do not fill. */
    const TEXT_DRAW_STROKE = 1;
    
    /** Fill the text only. Do not stroke. */
    const TEXT_DRAW_FILL = 2;
    
    /** Fill and stroke the text. */
    const TEXT_DRAW_FILL_AND_STROKE = 2;
    
    
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
    
    private $fontMap = [
        AbstractText::FONT_COURIER => [
            AbstractText::FONT_STYLE_NORMAL => ZendFont::FONT_COURIER,
            AbstractText::FONT_STYLE_BOLD => ZendFont::FONT_COURIER_BOLD,
            AbstractText::FONT_STYLE_ITALIC => ZendFont::FONT_COURIER_ITALIC,
            AbstractText::FONT_STYLE_BOLD_ITALIC => ZendFont::FONT_COURIER_BOLD_ITALIC,
        ],
        AbstractText::FONT_HELVETICA => [
            AbstractText::FONT_STYLE_NORMAL => ZendFont::FONT_HELVETICA,
            AbstractText::FONT_STYLE_BOLD => ZendFont::FONT_HELVETICA_BOLD,
            AbstractText::FONT_STYLE_ITALIC => ZendFont::FONT_HELVETICA_ITALIC,
            AbstractText::FONT_STYLE_BOLD_ITALIC => ZendFont::FONT_HELVETICA_BOLD_ITALIC,
        ],
        AbstractText::FONT_TIMES => [
            AbstractText::FONT_STYLE_NORMAL => ZendFont::FONT_TIMES,
            AbstractText::FONT_STYLE_BOLD => ZendFont::FONT_TIMES_BOLD,
            AbstractText::FONT_STYLE_ITALIC => ZendFont::FONT_TIMES_ITALIC,
            AbstractText::FONT_STYLE_BOLD_ITALIC => ZendFont::FONT_TIMES_BOLD_ITALIC,
        ],
    ];
    
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
        $this->translateAndClipToViewport($page, $graphic->getViewport(), $x, $y, $width, $height, $keepRatio);
        foreach ($graphic->getElements() as $element) {
            if ($element instanceof Text) {
                $this->drawText($page, $element);
            } elseif ($element instanceof PathText) {
                // TODO: VG-11: implement text on path
            } elseif ($element instanceof Shape) {
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
    private function translateAndClipToViewport(
        ZendPage $page,
        Viewport $viewport,
        $x, $y, $width, $height,
        $keepRatio = true
    ) {
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
        // clip viewport
        $page->clipRectangle(
            $viewport->getX(),
            $viewport->getY(),
            $viewport->getX() + $viewport->getHeight(),
            $viewport->getY() + $viewport->getWidth()
        );
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
                $xObj = new ZendNumericObject($element->getDestX());
                $yObj = new ZendNumericObject($element->getDestY());
                $content .= $xObj->toString() . ' ' . $yObj->toString() . ' ' . " m\n";
            } elseif ($element instanceof LineTo) {
                $xObj = new ZendNumericObject($element->getDestX());
                $yObj = new ZendNumericObject($element->getDestY());
                $content .= $xObj->toString() . ' ' . $yObj->toString() . ' ' . " l\n";
            } elseif ($element instanceof CurveTo) {
                $x1Obj = new ZendNumericObject($element->getControl1X());
                $y1Obj = new ZendNumericObject($element->getControl1Y());
                $x2Obj = new ZendNumericObject($element->getControl2X());
                $y2Obj = new ZendNumericObject($element->getControl2Y());
                $x3Obj = new ZendNumericObject($element->getDestX());
                $y3Obj = new ZendNumericObject($element->getDestY());
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
     * @param Text $element
     *
     * @throws \Exception
     */
    private function drawText(ZendPage $page, Text $element)
    {
        $filled = $element->getFillColor() !== null && $element->getFillOpacity() > 0;
        $stroked = $element->getStrokeColor() !== null && $element->getStrokeOpacity() > 0 && $element->getStrokeWidth() > 0;
        if ($element->getOpacity() === 0 || (!$filled && !$stroked)) {
            // not visible => do nothing
            return;
        }
        
        $page->saveGS();
        if ($element->getRotation() > 0) {
            $page->rotate($element->getX(), $element->getY(), -$element->getRotation() / 180. * pi());
        }
        
        $font = $this->getZendFont($element->getFontName(), $element->getFontStyle());
        $fontSize = $element->getFontSize();
        $page->setFont($font, $fontSize);
        
        $encodedText = $font->encodeString($element->getText(), 'UTF-8');
        list($x, $y) = $this->computeTextAnchor($element, $font, $fontSize);
        
        if (!$filled) {
            $this->setLineStyle($page, $element, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_STROKE);
        } elseif (!$stroked) {
            $this->setFillStyle($page, $element, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_FILL);
        } elseif ($element->getFillOpacity() !== 1 || $element->getStrokeOpacity() !== 1) {
            // separate fill and stroke to emulate correct alpha behavior
            $this->setFillStyle($page, $element, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_FILL);
            
            $this->setLineStyle($page, $element, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_STROKE);
        } else {
            $this->setLineStyle($page, $element);
            $this->setFillStyle($page, $element);
            $page->setAlpha($element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_FILL_AND_STROKE);
        }
        $page->restoreGS();
    }
    
    /**
     * @param Text $element
     * @param ZendAbstractFont $font
     * @param int $fontSize
     *
     * @return float[] [$x, $y]
     */
    private function computeTextAnchor(Text $element, ZendAbstractFont $font, $fontSize)
    {
        $scale = (float) $fontSize / (float) $font->getUnitsPerEm();
        $x = $element->getX();
        $y = $element->getY();
        switch ($element->getHAlign()) {
            case AbstractText::HORIZONTAL_ALIGN_MIDDLE:
                $glyphNumbers = $font->glyphNumbersForCharacters(TextUtils::getOrds($element->getText()));
                $x -= 0.5 * $scale * array_sum($font->widthsForGlyphs($glyphNumbers));
                break;
            case AbstractText::HORIZONTAL_ALIGN_RIGHT:
                $glyphNumbers = $font->glyphNumbersForCharacters(TextUtils::getOrds($element->getText()));
                $x -= $scale * array_sum($font->widthsForGlyphs($glyphNumbers));
                break;
        }
        switch ($element->getVAlign()) {
            case AbstractText::VERTICAL_ALIGN_TOP:
                $y -= $scale * ($font->getAscent() - $font->getDescent());
                break;
            case AbstractText::VERTICAL_ALIGN_CENTRAL:
                $y -= 0.5 * $scale * $font->getAscent();
                break;
            case AbstractText::VERTICAL_ALIGN_BOTTOM:
                $y -= $scale * ($font->getDescent() + $font->getDescent());
                break;
        }
        return [$x, $y];
    }
    
    /**
     * @param ZendPage $page
     * @param string $text
     * @param float $x
     * @param float $y
     * @param int $renderMode
     *
     * @return ZendPage
     */
    private function drawRawText(ZendPage $page, $text, $x, $y, $renderMode = 0)
    {
        $textObj = new ZendStringObject($text);
        $xObj    = new ZendNumericObject($x);
        $yObj    = new ZendNumericObject($y);
        $rObj    = new ZendNumericObject($renderMode);
        
        $content = "BT\n"
            . $xObj->toString() . ' ' . $yObj->toString() . " Td\n"
            . ((0 === $renderMode) ? '' : ($rObj->toString() . " Tr\n"))
            . $textObj->toString() . " Tj\n"
            . "ET\n";
        return $page->rawWrite($content, 'Text');
    }
    
    /**
     * @param ZendPage $page
     * @param Shape|AbstractText $shape
     * @param float|null $opacity
     */
    private function setLineStyle(ZendPage $page, $shape, $opacity = null)
    {
        $page->setLineWidth($shape->getStrokeWidth());
        $page->setLineColor($this->getZendColor($shape->getStrokeColor()));
        if ($opacity !== null) {
            $page->setAlpha($opacity * $shape->getStrokeOpacity());
        }
    }
    
    /**
     * @param ZendPage $page
     * @param Shape|AbstractText $shape
     * @param float|null $opacity
     */
    private function setFillStyle(ZendPage $page, $shape, $opacity = null)
    {
        $page->setFillColor($this->getZendColor($shape->getFillColor()));
        if ($opacity !== null) {
            $page->setAlpha($opacity * $shape->getFillOpacity());
        }
    }
    
    /**
     * @param $colorData
     *
     * @return ZendColor
     * @throws InvalidArgumentException
     */
    private function getZendColor($colorData)
    {
        if(is_string($colorData))
        {
            return ZendHtmlColor::color($colorData);
        }
        
        if(!$colorData instanceof ZendColor)
        {
            throw new InvalidArgumentException('Wrong color value, expected string or object of ' . ZendColor::class . ' class.');
        }
        
        return $colorData;
    }
    
    /**
     * @param string $fontName
     * @param string $fontStyle
     *
     * @return ZendAbstractFont
     * @throws \Exception
     */
    protected function getZendFont($fontName, $fontStyle = AbstractText::FONT_STYLE_NORMAL)
    {
        if (!isset($this->fontMap[$fontName][$fontStyle])) {
            // TODO: cleanup exceptions
            throw new \Exception('Font not fount: ' . $fontName . '/' . $fontStyle);
        }
        return ZendFont::fontWithName($this->fontMap[$fontName][$fontStyle]);
    }
}
