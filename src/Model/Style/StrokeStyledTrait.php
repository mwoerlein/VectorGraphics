<?php
namespace VectorGraphics\Model\Style;

trait StrokeStyledTrait
{
    /** @var StrokeStyle */
    private $strokeStyle;
    
    /**
     * @param float $width
     * @param HtmlColor|string|null $color
     * @param float $opacity
     */
    protected function initStrokeStyle($width = 1., $color = null, $opacity = 1.)
    {
        $this->strokeStyle = new StrokeStyle($width, $color, $opacity);
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
