<?php
namespace VectorGraphics\Model\Style;

use VectorGraphics\Model\Style\StyleInterface;

class FontStyle implements StyleInterface
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
    
    /** @var string  */
    private $name = self::FONT_TIMES;
    /** @var string TODO: rename property*/
    private $style = self::FONT_STYLE_NORMAL;
    /** @var int */
    private $size = 12;
    /** @var string */
    private $hAlign = self::HORIZONTAL_ALIGN_LEFT;
    /** @var string */
    private $vAlign = self::VERTICAL_ALIGN_BASE;
    
    /**
     * @param int $size
     * @param string $name
     * @param string $style
     * @param string $hAlign
     * @param string $vAlign
     */
    public function __construct(
        $size = 12,
        $name = FontStyle::FONT_TIMES,
        $style = FontStyle::FONT_STYLE_NORMAL,
        $hAlign = FontStyle::HORIZONTAL_ALIGN_LEFT,
        $vAlign = FontStyle::VERTICAL_ALIGN_BASE
    ) {
        $this->setSize($size);
        $this->setName($name);
        $this->setStyle($style);
        $this->align($hAlign, $vAlign);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }
    
    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }
    
    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
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
     * @return bool
     */
    public function isVisible()
    {
        return $this->getSize() > 0;
    }
}