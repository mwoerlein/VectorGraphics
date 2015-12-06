<?php
namespace VectorGraphics\Tests\Model\Anchor;

use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockBuilder as MockObject;
use VectorGraphics\Model\Anchor;
use VectorGraphics\Model\Anchor\SectionInterface;
use VectorGraphics\Model\Anchor\TableElement;

/**
 * @covers VectorGraphics\Model\Anchor\TableElement
 */
class TableElementTest extends TestCase
{
    public function testConstructor()
    {
        /** @var Anchor|MockObject $anchor */
        $anchor = $this->getMockBuilder(Anchor::class)->disableOriginalConstructor()->getMock();
        /** @var SectionInterface|MockObject $section */
        $section = $this->getMock(SectionInterface::class);
        
        $element = new TableElement(1, 0.2, $section, $anchor);
        $this->assertSame(1., $element->pos);
        $this->assertSame(0.2, $element->sectionPos);
        $this->assertSame($section, $element->section);
        $this->assertSame($anchor, $element->anchor);
    }
}
