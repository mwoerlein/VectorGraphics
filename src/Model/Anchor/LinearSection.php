<?php
namespace VectorGraphics\Model\Anchor;

use VectorGraphics\Model\Anchor;

class LinearSection implements SectionInterface
{
    /** @var float */
    private $x;
    
    /** @var float */
    private $y;
    
    /** @var float */
    private $tx;
    
    /** @var float */
    private $ty;
    
    /**
     * @param float $x0
     * @param float $y0
     * @param float $x1
     * @param float $y1
     */
    public function __construct($x0, $y0, $x1, $y1)
    {
        $this->x = (float) $x0;
        $this->y = (float) $y0;
        $this->tx = (float) ($x1 - $x0);
        $this->ty = (float) ($y1 - $y0);
    }
    
    /**
     * @param float $pos in [0.,1.]
     *
     * @return Anchor
     */
    public function getAnchor($pos) {
        return new Anchor(
            $this->x + $pos * $this->tx,
            $this->y + $pos * $this->ty,
            $this->tx,
            $this->ty
        );
    }
    
    /**
     * @return int
     */
    public function segmentCount() {
        return 1;
    }
}
