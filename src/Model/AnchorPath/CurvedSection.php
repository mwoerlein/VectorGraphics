<?php
namespace VectorGraphics\Model\AnchorPath;

class CurvedSection implements SectionInterface
{
    /** @var float */
    private $a;
    
    /** @var float */
    private $b;
    
    /** @var float */
    private $c;
    
    /** @var float */
    private $d;
    
    /** @var float */
    private $e;
    
    /** @var float */
    private $f;
    
    /** @var float */
    private $g;
    
    /** @var float */
    private $h;
    
    /**
     * @param float $x0
     * @param float $y0
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @param float $x3
     * @param float $y3
     */
    public function __construct($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3)
    {
        $this->a = (float) $x3 - 3 * $x2 + 3 * $x1 - $x0;
        $this->b = (float) 3 * $x2 - 6 * $x1 + 3 * $x0;
        $this->c = (float) 3 * $x1 - 3 * $x0;
        $this->d = (float) $x0;
        
        $this->e = (float) $y3 - 3 * $y2 + 3 * $y1 - $y0;
        $this->f = (float) 3 * $y2 - 6 * $y1 + 3 * $y0;
        $this->g = (float) 3 * $y1 - 3 * $y0;
        $this->h = (float) $y0;
    }
    
    /**
     * @param float $pos in [0.,1.]
     *
     * @return Anchor
     */
    public function getAnchor($pos) {
        $pos2 = $pos * $pos;
        $pos3 = $pos2 * $pos;
        return new Anchor(
              $this->a*$pos3 +   $this->b*$pos2 + $this->c*$pos + $this->d,
              $this->e*$pos3 +   $this->f*$pos2 + $this->g*$pos + $this->h,
            3*$this->a*$pos2 + 2*$this->b*$pos  + $this->c,
            3*$this->e*$pos2 + 2*$this->f*$pos  + $this->g
        );
    }
    
    /**
     * @return int
     */
    public function segmentCount() {
        return 100;
    }
}
