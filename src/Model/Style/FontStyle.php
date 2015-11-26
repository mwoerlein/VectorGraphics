<?php
namespace VectorGraphics\Model\Style;

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
        $this->setHAlign($hAlign);
        $this->setVAlign($vAlign);
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
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     *
     * @return $this
     */
    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
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
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getHAlign()
    {
        return $this->hAlign;
    }
    
    /**
     * @param string $hAlign
     *
     * @return $this
     */
    public function setHAlign($hAlign)
    {
        $this->hAlign = $hAlign;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getVAlign()
    {
        return $this->vAlign;
    }
    
    /**
     * @param string $vAlign
     *
     * @return $this
     */
    public function setVAlign($vAlign)
    {
        $this->vAlign = $vAlign;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->getSize() > 0;
    }
    
    /**
     * @param FontStyle $style
     */
    public function update(FontStyle $style)
    {
        $this->setName($style->getName());
        $this->setStyle($style->getStyle());
        $this->setSize($style->getSize());
        $this->setHAlign($style->getHAlign());
        $this->setVAlign($style->getVAlign());
    }
}
