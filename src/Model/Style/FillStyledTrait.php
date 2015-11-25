<?php
namespace VectorGraphics\Model\Style;

trait FillStyledTrait
{
    /** @var FillStyle */
    private $fillStyle;
    
    /**
     * @param HtmlColor|string|null $color
     * @param float $opacity
     */
    protected function initFillStyle($color = null, $opacity = 1.)
    {
        $this->fillStyle = new FillStyle($color, $opacity);
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
}
