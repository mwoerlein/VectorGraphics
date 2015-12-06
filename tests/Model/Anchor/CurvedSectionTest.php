<?php
namespace VectorGraphics\Tests\Model\Anchor;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Anchor;
use VectorGraphics\Model\Anchor\CurvedSection;
use VectorGraphics\Model\Anchor\SectionInterface;

/**
 * @covers VectorGraphics\Model\Anchor\CurvedSection
 */
class CurvedSectionTest extends TestCase
{
    public function testSegmentCount()
    {
        $section = new CurvedSection(1, 2, 3, 4, 5, 6, 7, 8);
        $this->assertSame(100, $section->segmentCount());
    }
    
    public function anchorProvider()
    {
        $data = [];
        $cScale = 4. * (sqrt(2.) - 1.) / 3.;
        $data['1/4 circle'] = [
            'section' => new CurvedSection(0, 1, $cScale, 1, 1, $cScale, 1, 0),
            'anchors' => [
                "0." => new Anchor(0.0, 1.0, 1.0, 0.0),
                ".3" => new Anchor(0.45955757467537994, 0.88838181771801994, 0.88781593454508534, -0.46019872486550489),
                ".5" => new Anchor(sqrt(2.) / 2., sqrt(2.) / 2., sqrt(2.) / 2., -sqrt(2.) / 2.),
                ".8" => new Anchor(0.94901933598375621, 0.31607734393502462, 0.31585509407193302, -0.9488074407111331),
                "1." => new Anchor(1.0, 0.0, 0.0, -1.0),
            ]
        ];
    
        $data['translated linear curve'] = [
            'section' => new CurvedSection(1, 2, 3, 4, 5, 6, 7, 8),
            'anchors' => [
                "0." => new Anchor(1.0, 2.0, sqrt(2.) / 2., sqrt(2.) / 2.),
                ".3" => new Anchor(2.8, 3.8, sqrt(2.) / 2., sqrt(2.) / 2.),
                ".5" => new Anchor(4.0, 5.0, sqrt(2.) / 2., sqrt(2.) / 2.),
                ".8" => new Anchor(5.8, 6.8, sqrt(2.) / 2., sqrt(2.) / 2.),
                "1." => new Anchor(7.0, 8.0, sqrt(2.) / 2., sqrt(2.) / 2.),
            ]
        ];
        
        $data['some curve'] = [
            'section' => new CurvedSection(1, 3, 0, -1, -8, 6, 2, 2),
            'anchors' => [
                "0." => new Anchor(1.0, 3.0, -0.242535625036333, -0.97014250014533199),
                ".2" => new Anchor(-0.240, 1.744, -0.98562225481326671, -0.16896381511084566),
                ".5" => new Anchor(-2.625, 2.5, -0.75925660236529657, 0.65079137345596849),
                ".7" => new Anchor(-2.815, 3.224, 0.91947270404304893, 0.39315384586668339),
                "1." => new Anchor(2.0, 2.0, 0.92847669088525941, -0.37139067635410378),
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
