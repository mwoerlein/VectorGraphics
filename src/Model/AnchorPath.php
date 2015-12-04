<?php
namespace VectorGraphics\Model;

use VectorGraphics\Model\AnchorPath\Anchor;
use VectorGraphics\Model\AnchorPath\CurvedSection;
use VectorGraphics\Model\AnchorPath\LinearSection;
use VectorGraphics\Model\AnchorPath\SectionInterface;
use VectorGraphics\Model\AnchorPath\TableElement;
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
        $t0 = $elem0->section === $elem1->section ? $elem0->sectionPos : 0.; 
        return $elem1->section->getAnchor($t0 + $scale * ($elem1->sectionPos - $t0));
    }
    
    /**
     * @param Path $path
     */
    private function initTable(Path $path)
    {
        $curX = 0;
        $curY = 0;
        foreach ($path->getElements() as $path) {
            if ($path instanceof LineTo) {
                $this->append(new LinearSection($curX, $curY, $path->getDestX(), $path->getDestY()));
            } elseif ($path instanceof CurveTo) {
                $this->append(new CurvedSection(
                    $curX, $curY,
                    $path->getControl1X(), $path->getControl1Y(),
                    $path->getControl2X(), $path->getControl2Y(),
                    $path->getDestX(), $path->getDestY()
                ));
            }
            $curX = $path->getDestX();
            $curY = $path->getDestY();
        }
    }
    
    private function append(SectionInterface $section) {
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
