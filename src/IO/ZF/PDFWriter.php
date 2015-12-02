<?php
namespace VectorGraphics\IO\ZF;

use VectorGraphics\IO\AbstractWriter;
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Graphic\Viewport;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\LineTo;
use VectorGraphics\Model\Path\MoveTo;
use VectorGraphics\Model\Shape\AbstractShape;
use VectorGraphics\Model\Style\FillStyle;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\HtmlColor;
use VectorGraphics\Model\Style\StrokeStyle;
use VectorGraphics\Model\Text\PathText;
use VectorGraphics\Model\Text\Text;
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
    const TEXT_DRAW_FILL = 0;
    
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
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private $fontMap = [
        FontStyle::FONT_COURIER => [
            FontStyle::FONT_STYLE_NORMAL => ZendFont::FONT_COURIER,
            FontStyle::FONT_STYLE_BOLD => ZendFont::FONT_COURIER_BOLD,
            FontStyle::FONT_STYLE_ITALIC => ZendFont::FONT_COURIER_ITALIC,
            FontStyle::FONT_STYLE_BOLD_ITALIC => ZendFont::FONT_COURIER_BOLD_ITALIC,
        ],
        FontStyle::FONT_HELVETICA => [
            FontStyle::FONT_STYLE_NORMAL => ZendFont::FONT_HELVETICA,
            FontStyle::FONT_STYLE_BOLD => ZendFont::FONT_HELVETICA_BOLD,
            FontStyle::FONT_STYLE_ITALIC => ZendFont::FONT_HELVETICA_ITALIC,
            FontStyle::FONT_STYLE_BOLD_ITALIC => ZendFont::FONT_HELVETICA_BOLD_ITALIC,
        ],
        FontStyle::FONT_TIMES => [
            FontStyle::FONT_STYLE_NORMAL => ZendFont::FONT_TIMES,
            FontStyle::FONT_STYLE_BOLD => ZendFont::FONT_TIMES_BOLD,
            FontStyle::FONT_STYLE_ITALIC => ZendFont::FONT_TIMES_ITALIC,
            FontStyle::FONT_STYLE_BOLD_ITALIC => ZendFont::FONT_TIMES_BOLD_ITALIC,
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
            if (!$element->isVisible()) {
                continue;
            }
            if ($element instanceof Text) {
                $this->drawText($page, $element);
            } elseif ($element instanceof PathText) {
                // TODO: VG-11: implement text on path
            } elseif ($element instanceof AbstractShape) {
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
     * @param AbstractShape $shape
     *
     * @throws \Exception
     */
    private function drawShape(ZendPage $page, AbstractShape $shape)
    {
        $fillStyle = $shape->getFillStyle();
        $strokeStyle = $shape->getStrokeStyle();
        $path = $shape->getPath();
    
        $page->saveGS();
        if (!$fillStyle->isVisible()) {
            $this->setLineStyle($page, $strokeStyle, $shape->getOpacity());
            $this->drawRawPath($page, $path, ZendPage::SHAPE_DRAW_STROKE);
        } elseif (!$strokeStyle->isVisible()) {
            $this->setFillStyle($page, $fillStyle, $shape->getOpacity());
            $this->drawRawPath($page, $path, ZendPage::SHAPE_DRAW_FILL);
        } elseif ($fillStyle->getOpacity() !== 1. || $strokeStyle->getOpacity() !== 1.) {
            // separate fill and stroke to emulate correct alpha behavior
            $this->setFillStyle($page, $fillStyle, $shape->getOpacity());
            $this->drawRawPath($page, $path, ZendPage::SHAPE_DRAW_FILL);
            
            $this->setLineStyle($page, $strokeStyle, $shape->getOpacity());
            $this->drawRawPath($page, $path, ZendPage::SHAPE_DRAW_STROKE);
        } else {
            $this->setLineStyle($page, $strokeStyle);
            $this->setFillStyle($page, $fillStyle);
            $page->setAlpha($shape->getOpacity());
            $this->drawRawPath($page, $path, ZendPage::SHAPE_DRAW_FILL_AND_STROKE);
        }
        $page->restoreGS();
    }
    
    /**
     * @param ZendPage $page
     * @param Text $element
     *
     * @throws \Exception
     */
    private function drawText(ZendPage $page, Text $element)
    {
        $fillStyle = $element->getFillStyle();
        $strokeStyle = $element->getStrokeStyle();
        $fontStyle = $element->getFontStyle();
        
        $page->saveGS();
        if ($element->getRotation() > 0) {
            $page->rotate($element->getX(), $element->getY(), -$element->getRotation() / 180. * pi());
        }
        
        /** @var float $x */
        /** @var float $y */
        /** @var ZendAbstractFont $font */
        list($x, $y, $font) = $this->computeTextAnchor($element);
        $page->setFont($font, $fontStyle->getSize());
        $encodedText = $font->encodeString($element->getText(), 'UTF-8');
        
        if (!$fillStyle->isVisible()) {
            $this->setLineStyle($page, $strokeStyle, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_STROKE);
        } elseif (!$strokeStyle->isVisible()) {
            $this->setFillStyle($page, $fillStyle, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_FILL);
        } elseif ($fillStyle->getOpacity() !== 1. || $strokeStyle->getOpacity() !== 1.) {
            // separate fill and stroke to emulate correct alpha behavior
            $this->setFillStyle($page, $fillStyle, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_FILL);
            
            $this->setLineStyle($page, $strokeStyle, $element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_STROKE);
        } else {
            $this->setLineStyle($page, $strokeStyle);
            $this->setFillStyle($page, $fillStyle);
            $page->setAlpha($element->getOpacity());
            $this->drawRawText($page, $encodedText, $x, $y, self::TEXT_DRAW_FILL_AND_STROKE);
        }
        $page->restoreGS();
    }
    
    /**
     * @param ZendPage $page
     * @param Path $path
     * @param int $fillType
     *
     * @return ZendPage
     * @throws \Exception
     */
    private function drawRawPath(ZendPage $page, Path $path, $fillType)
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
     * @param Text $element
     *
     * @return mixed[] [float $x, float $y, ZendAbstractFont $font]
     */
    private function computeTextAnchor(Text $element)
    {
        $fontStyle = $element->getFontStyle();
        $font = $this->getZendFont($fontStyle);
        $scale = $fontStyle->getSize() / (float) $font->getUnitsPerEm();
        $x = $element->getX();
        $y = $element->getY();
        switch ($fontStyle->getHAlign()) {
            case FontStyle::HORIZONTAL_ALIGN_MIDDLE:
                $glyphNumbers = $font->glyphNumbersForCharacters(TextUtils::getOrds($element->getText()));
                $x -= 0.5 * $scale * array_sum($font->widthsForGlyphs($glyphNumbers));
                break;
            case FontStyle::HORIZONTAL_ALIGN_RIGHT:
                $glyphNumbers = $font->glyphNumbersForCharacters(TextUtils::getOrds($element->getText()));
                $x -= $scale * array_sum($font->widthsForGlyphs($glyphNumbers));
                break;
        }
        switch ($fontStyle->getVAlign()) {
            case FontStyle::VERTICAL_ALIGN_TOP:
                $y -= $scale * ($font->getAscent() - $font->getDescent());
                break;
            case FontStyle::VERTICAL_ALIGN_CENTRAL:
                $y -= 0.5 * $scale * $font->getAscent();
                break;
            case FontStyle::VERTICAL_ALIGN_BOTTOM:
                $y -= $scale * ($font->getDescent() + $font->getDescent());
                break;
        }
        return [$x, $y, $font];
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
     * @param StrokeStyle $strokeStyle
     * @param float|null $opacity
     */
    private function setLineStyle(ZendPage $page, StrokeStyle $strokeStyle, $opacity = null)
    {
        $page->setLineWidth($strokeStyle->getWidth());
        $page->setLineColor($this->getZendColor($strokeStyle->getColor()));
        if ($opacity !== null) {
            $page->setAlpha($opacity * $strokeStyle->getOpacity());
        }
    }
    
    /**
     * @param ZendPage $page
     * @param FillStyle $fillStyle
     * @param float|null $opacity
     */
    private function setFillStyle(ZendPage $page, FillStyle $fillStyle, $opacity = null)
    {
        $page->setFillColor($this->getZendColor($fillStyle->getColor()));
        if ($opacity !== null) {
            $page->setAlpha($opacity * $fillStyle->getOpacity());
        }
    }
    
    /**
     * @param HtmlColor $color
     *
     * @return ZendColor
     */
    private function getZendColor(HtmlColor $color)
    {
        return ZendHtmlColor::color($color->__toString());
    }
    
    /**
     * @param string|FontStyle $fontStyle
     *
     * @return ZendAbstractFont
     * @throws \Exception
     */
    protected function getZendFont(FontStyle $fontStyle)
    {
        if (!isset($this->fontMap[$fontStyle->getName()][$fontStyle->getStyle()])) {
            // TODO: cleanup exceptions
            throw new \Exception('Font not fount: ' . $fontStyle);
        }
        return ZendFont::fontWithName($this->fontMap[$fontStyle->getName()][$fontStyle->getStyle()]);
    }
}
