<?php
namespace VectorGraphics\Model\Anchor;

use VectorGraphics\Model\Anchor;

class TableElement
{
    /** @var float */
    public $pos;
    
    /** @var float [0.,1.]*/
    public $sectionPos;
    
    /** @var SectionInterface */
    public $section;
    
    /** @var Anchor */
    public $anchor;
    
    /**
     * TableElement constructor.
     *
     * @param float $pos
     * @param float $sectionPos
     * @param SectionInterface $section
     * @param Anchor $anchor
     */
    public function __construct($pos, $sectionPos, SectionInterface $section, Anchor $anchor)
    {
        $this->pos = $pos;
        $this->sectionPos = $sectionPos;
        $this->section = $section;
        $this->anchor = $anchor;
    }
}
