<?php
namespace VectorGraphics\Model;

use VectorGraphics\Model\Graphic\GraphicElement;
use VectorGraphics\Model\Style\FillStyle;
use VectorGraphics\Model\Style\HtmlColor;
use VectorGraphics\Model\Style\StrokeStyle;

abstract class Shape extends GraphicElement
{
    /** @var FillStyle */
    private $fillStyle;
    
    /** @var StrokeStyle */
    private $strokeStyle;
    
    /**
     */
    public function __construct()
    {
        $this->fillStyle = new FillStyle(); // no fill
        $this->strokeStyle = new StrokeStyle(1, "black", 1.); // black stroke
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
    
    /**
     * @return Path
     */
    abstract public function getPath();
}
