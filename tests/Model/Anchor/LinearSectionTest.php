<?php
namespace VectorGraphics\Tests\Model\Anchor;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Anchor;
use VectorGraphics\Model\Anchor\LinearSection;
use VectorGraphics\Model\Anchor\SectionInterface;

/**
 * @covers VectorGraphics\Model\Anchor\LinearSection
 */
class LinearSectionTest extends TestCase
{
    public function testSegmentCount()
    {
        $section = new LinearSection(1, 2, 3, 4);
        $this->assertSame(1, $section->segmentCount());
    }
    
    public function anchorProvider()
    {
        $data = [];
        $data['x-axis'] = [
            'section' => new LinearSection(0, 0, 1, 0),
            'anchors' => [
                "0." => new Anchor(0.0, 0.),
                ".3" => new Anchor(0.3, 0.),
                ".5" => new Anchor(0.5, 0.),
                ".7" => new Anchor(0.7, 0.),
                "1." => new Anchor(1.0, 0.),
            ]
        ];
        $data['3*y-axis'] = [
            'section' => new LinearSection(0, 0, 0, 3),
            'anchors' => [
                "0." => new Anchor(0., 0.0, 0, 1),
                ".3" => new Anchor(0., 0.9, 0, 1),
                ".5" => new Anchor(0., 1.5, 0, 1),
                ".7" => new Anchor(0., 2.1, 0, 1),
                "1." => new Anchor(0., 3.0, 0, 1),
            ]
        ];
        
        $data['3/-4 translated'] = [
            'section' => new LinearSection(1, 1, 10, -11),
            'anchors' => [
                "0." => new Anchor(1.0,  1.0, 3, -4),
                ".2" => new Anchor(2.8, -1.4, 3, -4),
                ".5" => new Anchor(5.5, -5.0, 3, -4),
                ".8" => new Anchor(8.2, -8.6, 3, -4),
                "1." => new Anchor(10., -11., 3, -4),
            ]
        ];
        return $data;
    }
    
    /**
     * @param SectionInterface $section
     * @param Anchor[] $anchors
     *
     * @dataProvider anchorProvider
     */
    public function testGetAnchor(SectionInterface $section, array $anchors)
    {
        $results = [];
        foreach ($anchors as $pos => $anchor) {
            $results[$pos]= $section->getAnchor((float) $pos);
        }
        $this->assertEquals($anchors, $results);
    }
}
