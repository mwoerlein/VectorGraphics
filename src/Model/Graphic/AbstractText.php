<?php
namespace VectorGraphics\Model\Graphic;

use VectorGraphics\Model\Style\FillStyledTrait;
use VectorGraphics\Model\Style\FontStyledTrait;
use VectorGraphics\Model\Style\StrokeStyledTrait;

class AbstractText extends GraphicElement
{
    use StrokeStyledTrait, FillStyledTrait, FontStyledTrait;
    
    /** @var string */
    private $text;
    
    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = str_replace(["\r\n", "\r", "\n"], '', $text); // ignore newlines
        $this->initFontStyle(); // 12 point Times, left aligned
        $this->initFillStyle("black"); // black fill
        $this->initStrokeStyle(); // no stroke
    }
    
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->getFontStyle()->isVisible()
            && ($this->getFillStyle()->isVisible() || $this->getStrokeStyle()->isVisible());
    }
}
