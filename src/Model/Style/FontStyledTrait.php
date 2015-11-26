<?php
namespace VectorGraphics\Model\Style;

trait FontStyledTrait
{
    /** @var FontStyle */
    private $fontStyle;
    
    /**
     * @param int $size
     * @param string $name
     * @param string $style
     * @param string $hAlign
     * @param string $vAlign
     */
    protected function initFontStyle(
        $size = 12,
        $name = FontStyle::FONT_TIMES,
        $style = FontStyle::FONT_STYLE_NORMAL,
        $hAlign = FontStyle::HORIZONTAL_ALIGN_LEFT,
        $vAlign = FontStyle::VERTICAL_ALIGN_BASE
    ) {
        $this->fontStyle = new FontStyle($size, $name, $style, $hAlign, $vAlign);
    }
    
    /**
     * @return FontStyle
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }
    
    /**
     * @param int $fontSize
     * @param string $fontName
     * @param string $fontStyle
     *
     * @return $this
     */
    public function setFont(
        $fontSize = 12,
        $fontName = FontStyle::FONT_TIMES,
        $fontStyle = FontStyle::FONT_STYLE_NORMAL
    ) {
        $this->fontStyle->setSize($fontSize);
        $this->fontStyle->setName($fontName);
        $this->fontStyle->setStyle($fontStyle);
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
        $this->fontStyle->setHAlign($hAlign);
        $this->fontStyle->setVAlign($vAlign);
        return $this;
    }
}
