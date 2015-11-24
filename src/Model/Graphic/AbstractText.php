<?php
namespace VectorGraphics\Model\Graphic;

use VectorGraphics\Model\Style\FillStyle;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\HtmlColor;
use VectorGraphics\Model\Style\StrokeStyle;

class AbstractText extends GraphicElement
{
    
    /** @var string */
    private $text;
    
    /** @var FontStyle */
    private $fontStyle;
    
    /** @var FillStyle */
    private $fillStyle;
    
    /** @var StrokeStyle */
    private $strokeStyle;
    
    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = str_replace(["\r\n", "\r", "\n"], '', $text); // ignore newlines
        $this->fontStyle = new FontStyle( // 12 Points Times, left aligned
            12,
            FontStyle::FONT_TIMES,
            FontStyle::FONT_STYLE_NORMAL,
            FontStyle::HORIZONTAL_ALIGN_LEFT,
            FontStyle::VERTICAL_ALIGN_BASE
        );
        $this->fillStyle = new FillStyle("black", 1.); // black fill
        $this->strokeStyle = new StrokeStyle(); // no stroke
    }
    
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * @return FontStyle
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }
    
    /**
     * @param string $fontName
     *
     * @return $this
     */
    public function setFontName($fontName)
    {
        $this->fontStyle->setName($fontName);
        return $this;
    }
    
    /**
     * @param string $fontStyle
     *
     * @return $this
     */
    public function setFontStyle($fontStyle)
    {
        $this->fontStyle->setStyle($fontStyle);
        return $this;
    }
    
    /**
     * @param int $fontSize
     *
     * @return $this
     */
    public function setFontSize($fontSize)
    {
        $this->fontStyle->setSize($fontSize);
        return $this;
    }
    
    /**
     * @param string $hAlign
     * @param string $vAlign
     *
     * @return $this
     */
    public function align($hAlign = FontStyle::HORIZONTAL_ALIGN_LEFT, $vAlign = FontStyle::VERTICAL_ALIGN_BASE)
    {
        $this->fontStyle->align($hAlign, $vAlign);
        return $this;
    }
    
    /**
     * @return FillStyle
     */
    public function getFillStyle()
    {
        return $this->fillStyle;
    }
    
    /**
     * @param HtmlColor|string|null $fillColor
     * @param float $fillOpacity
     *
     * @return $this
     */
    public function setFillColor($fillColor, $fillOpacity = 1.)
    {
        $this->fillStyle->setColor($fillColor, $fillOpacity);
        return $this;
    }
    
    /**
     * @param float $fillOpacity
     *
     * @return $this
     */
    public function setFillOpacity($fillOpacity)
    {
        $this->fillStyle->setOpacity($fillOpacity);
        return $this;
    }
    
    /**
     * @return StrokeStyle
     */
    public function getStrokeStyle()
    {
        return $this->strokeStyle;
    }
    
    /**
     * @param float $strokeWidth
     *
     * @return $this
     */
    public function setStrokeWidth($strokeWidth)
    {
        $this->strokeStyle->setWidth($strokeWidth);
        return $this;
    }
    
    /**
     * @param HtmlColor|string|null $strokeColor
     * @param float $strokeOpacity
     *
     * @return $this
     */
    public function setStrokeColor($strokeColor, $strokeOpacity = 1.)
    {
        $this->strokeStyle->setColor($strokeColor, $strokeOpacity);
        return $this;
    }
    
    /**
     * @param float $strokeOpacity
     *
     * @return $this
     */
    public function setStrokeOpacity($strokeOpacity)
    {
        $this->strokeStyle->setOpacity($strokeOpacity);
        return $this;
    }
}
