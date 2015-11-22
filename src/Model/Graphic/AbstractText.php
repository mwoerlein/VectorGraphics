<?php
namespace VectorGraphics\Model\Graphic;

class AbstractText extends GraphicElement
{
    const FONT_COURIER = "Courier";
    const FONT_HELVETICA = "Helvetica";
    const FONT_TIMES = "Times";
    
    const FONT_STYLE_NORMAL = "normal";
    const FONT_STYLE_BOLD = "bold";
    const FONT_STYLE_ITALIC = "italic";
    const FONT_STYLE_BOLD_ITALIC = "bold-italic";
    
    const HORIZONTAL_ALIGN_LEFT = 'left';
    const HORIZONTAL_ALIGN_MIDDLE = 'middle';
    const HORIZONTAL_ALIGN_RIGHT = 'right';
    
    const VERTICAL_ALIGN_TOP = 'top';
    const VERTICAL_ALIGN_CENTRAL = 'central';
    const VERTICAL_ALIGN_BASE = 'base';
    const VERTICAL_ALIGN_BOTTOM = 'bottom';
    
    /** @var string */
    private $text;
    
    // TODO: extract textStyle
    /** @var string  */
    private $fontName = self::FONT_TIMES;
    /** @var string */
    private $fontStyle = self::FONT_STYLE_NORMAL;
    /** @var int */
    private $fontSize = 12;
    /** @var string */
    private $hAlign = self::HORIZONTAL_ALIGN_LEFT;
    /** @var string */
    private $vAlign = self::VERTICAL_ALIGN_BASE;
    
    // TODO: extract fillStyle
    /** @var string */
    private $fillColor = 'black';
    /** @var float */
    private $fillOpacity = 1;
    
    // TODO: extract strokeStyle
    /** @var float */
    private $strokeWidth = 0;
    /** @var string */
    private $strokeColor = 'black';
    /** @var float */
    private $strokeOpacity = 1;
    
    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = str_replace(["\r\n", "\r", "\n"], '', $text);
    }
    
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * @return string
     */
    public function getFontName()
    {
        return $this->fontName;
    }
    
    /**
     * @param string $fontName
     */
    public function setFontName($fontName)
    {
        $this->fontName = $fontName;
    }
    
    /**
     * @return string
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }
    
    /**
     * @param string $fontStyle
     */
    public function setFontStyle($fontStyle)
    {
        $this->fontStyle = $fontStyle;
    }
    
    /**
     * @return int
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }
    
    /**
     * @param int $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }
    
    /**
     * @return string
     */
    public function getHAlign()
    {
        return $this->hAlign;
    }
    
    /**
     * @return string
     */
    public function getVAlign()
    {
        return $this->vAlign;
    }
    
    /**
     * @param string $hAlign
     * @param string $vAlign
     */
    public function align($hAlign = self::HORIZONTAL_ALIGN_LEFT, $vAlign = self::VERTICAL_ALIGN_BASE)
    {
        $this->hAlign = $hAlign;
        $this->vAlign = $vAlign;
    }
    
    /**
     * @return string|null
     */
    public function getFillColor()
    {
        return $this->fillColor;
    }
    
    /**
     * @param string|null $fillColor
     *
     * @return $this
     */
    public function setFillColor($fillColor)
    {
        $this->fillColor = $fillColor;
        return $this;
    }
    
    /**
     * @return float
     */
    public function getFillOpacity()
    {
        return $this->fillOpacity;
    }
    
    /**
     * @param float $fillOpacity
     *
     * @return $this
     */
    public function setFillOpacity($fillOpacity)
    {
        $this->fillOpacity = $fillOpacity;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getStrokeColor()
    {
        return $this->strokeColor;
    }
    
    /**
     * @param string|null $strokeColor
     *
     * @return $this
     */
    public function setStrokeColor($strokeColor)
    {
        $this->strokeColor = $strokeColor;
        return $this;
    }
    
    /**
     * @return float
     */
    public function getStrokeWidth()
    {
        return $this->strokeWidth;
    }
    
    /**
     * @param float $strokeWidth
     *
     * @return $this
     */
    public function setStrokeWidth($strokeWidth)
    {
        $this->strokeWidth = $strokeWidth;
        return $this;
    }
    
    /**
     * @return float
     */
    public function getStrokeOpacity()
    {
        return $this->strokeOpacity;
    }
    
    /**
     * @param float $strokeOpacity
     *
     * @return $this
     */
    public function setStrokeOpacity($strokeOpacity)
    {
        $this->strokeOpacity = $strokeOpacity;
        return $this;
    }
}
