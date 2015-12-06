<?php
namespace VectorGraphics\Model\Anchor;

use VectorGraphics\Model\Anchor;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Path\Close;
use VectorGraphics\Model\Path\CurveTo;
use VectorGraphics\Model\Path\LineTo;

class AnchorPath
{
    /** @var TableElement[] */
    public $table = [];
    
    public function __construct(Path $path)
    {
        $this->initTable($path);
    }
    
    public function isEmpty()
    {
        return 0 === count($this->table);
    }
    
    /**
     * @return float
     */
    public function getLen()
    {
        return $this->isEmpty() ? 0. : end($this->table)->pos;
    }
    
    /**
     * @param $pos
     *
     * @return Anchor|null
     */
    public function getAnchor($pos)
    {
        // binary search for matching table elements
        $left = 0;
        $right = count($this->table) - 1;
        
        if ($this->isEmpty() || $pos < $this->table[$left]->pos || $pos > $this->table[$right]->pos) {
            return null;
        }
    
        while ($left <= $right) {
            $mid = ($left + $right) >> 1;
        
            if ($this->table[$mid]->pos === $pos) {
                // exact hit => use it
                return $this->table[$mid]->anchor;
            } elseif ($this->table[$mid]->pos > $pos) {
                $right = $mid - 1;
            } elseif ($this->table[$mid]->pos < $pos) {
                $left = $mid + 1;
            }
        }
        
        $elem0 = $this->table[$right];
        $elem1 = $this->table[$left];
        // linear interpolation between elem-0 and elem-1
        $scale = ($pos - $elem0->pos) / ($elem1->pos - $elem0->pos);
        $sectionPos0 = $elem0->section === $elem1->section ? $elem0->sectionPos : 0.; 
        return $elem1->section->getAnchor($sectionPos0 + $scale * ($elem1->sectionPos - $sectionPos0));
    }
    
    /**
     * @param Path $path
     */
    private function initTable(Path $path)
    {
        $curX = 0.;
        $curY = 0.;
        foreach ($path->getElements() as $path) {
            $destX = $path->getDestX();
            $destY = $path->getDestY();
            if ($path instanceof LineTo || $path instanceof Close) {
                if ($curX === $destX && $curY === $destY) {
                    // skip empty lines
                    continue;
                }
                $this->addSection(new LinearSection($curX, $curY, $destX, $destY));
            } elseif ($path instanceof CurveTo) {
                $c1x = $path->getControl1X();
                $c1y = $path->getControl1Y();
                $c2x = $path->getControl2X();
                $c2y = $path->getControl2Y();
                if (
                    $curX === $destX && $curY === $destY
                    && $curX === $c1x && $curY === $c1y
                    && $curX === $c2x && $curY === $c2y
                ) {
                    // skip empty curve
                    continue;
                }
                $this->addSection(new CurvedSection($curX, $curY, $c1x, $c1y, $c2x, $c2y, $destX, $destY));
            }
            $curX = $destX;
            $curY = $destY;
        }
    }
    
    private function addSection(SectionInterface $section) {
        if ($this->isEmpty()) {
            $this->table[] = $curElement = new TableElement(0., 0., $section, $section->getAnchor(0.));
        } else {
            $curElement = end($this->table);
        }
        for ($i = 1; $i <= $section->segmentCount(); $i++) {
            $t = $i / (float) $section->segmentCount();
            $anchor = $section->getAnchor($t);
            $pos = $curElement->pos + $curElement->anchor->getDistanceTo($anchor);
            $this->table[] = $curElement = new TableElement($pos, $t, $section, $anchor);
        }
    }
}
