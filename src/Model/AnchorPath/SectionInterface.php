<?php
namespace VectorGraphics\Model\AnchorPath;

interface SectionInterface
{
    /**
     * @param float $pos in [0.,1.]
     *
     * @return Anchor
     */
    public function getAnchor($pos);
    
    /**
     * @return int
     */
    public function segmentCount();
}
