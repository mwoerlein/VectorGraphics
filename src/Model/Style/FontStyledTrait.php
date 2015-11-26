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
}
