<?php
namespace VectorGraphics\Model\Path;

class CurveTo extends PathElement {
    
    /** @var float */
    protected $control1X;
    
    /** @var float */
    protected $control1Y;
    
    /** @var float */
    protected $control2X;
    
    /** @var float */
    protected $control2Y;
    
    /**
     * @param float $control1X
     * @param float $control1Y
     * @param float $control2X
     * @param float $control2Y
     * @param float $destX
     * @param float $destY
     */
    public function __construct($control1X, $control1Y, $control2X, $control2Y, $destX, $destY)
    {
        parent::__construct($destX, $destY);
        $this->control1X = $control1X;
        $this->control1Y = $control1Y;
        $this->control2X = $control2X;
        $this->control2Y = $control2Y;
    }
    
    /**
     * @return float
     */
    public function getControl1X()
    {
        return $this->control1X;
    }
    
    /**
     * @return float
     */
    public function getControl1Y()
    {
        return $this->control1Y;
    }
    
    /**
     * @return float
     */
    public function getControl2X()
    {
        return $this->control2X;
    }
    
    /**
     * @return float
     */
    public function getControl2Y()
    {
        return $this->control2Y;
    }
}
