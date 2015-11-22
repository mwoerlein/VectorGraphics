<?php
namespace VectorGraphics\Model;

use VectorGraphics\Model\Graphic\GraphicElement;

abstract class Shape extends GraphicElement
{
    // TODO: extract fillStyle
    /** @var string */
    private $fillColor = null;
    /** @var float */
    private $fillOpacity = 1;
    
    // TODO: extract strokeStyle
    /** @var float */
    private $strokeWidth = 1;
    /** @var string */
    private $strokeColor = "black";
    /** @var float */
    private $strokeOpacity = 1;
    
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
    
    /**
     * @return Path
     */
    abstract public function getPath();
}
