<?php
namespace VectorGraphics\Tests\Model\Anchor;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Anchor;
use VectorGraphics\Model\Anchor\AnchorPath;
use VectorGraphics\Model\Path;

/**
 * @covers VectorGraphics\Model\Anchor\AnchorPath
 */
class AnchorPathTest extends TestCase
{
    public function anchorPathProvider()
    {
        $data = [];
        
        $data['empty path'] = [
            'path' => new Path(0, 0),
            'length' => 0.,
            'anchors' => [
                '0.' => null,
            ],
        ];
        
        $data['ignore empty lines and curves'] = [
            'path' => (new Path(0, 0))
                ->lineTo(0, 0)
                ->moveTo(1, 1)
                ->curveTo(1, 1, 1, 1, 1, 1)
                ->moveTo(0, 0)
                ->close(),
            'length' => 0.,
            'anchors' => [
                '0.' => null,
            ],
        ];
    
        $data['pentagram'] = [
            'path' => (new Path(sin(0./5. * pi()), cos(0./5. * pi())))
                ->lineTo(sin(4./5. * pi()), cos(4./5. * pi()))
                ->lineTo(sin(8./5. * pi()), cos(8./5. * pi()))
                ->lineTo(sin(2./5. * pi()), cos(2./5. * pi()))
                ->lineTo(sin(6./5. * pi()), cos(6./5. * pi()))
                ->close(),
            'length' => 10 * sin(2./5. * pi()), // => 9.5105651629515364
            'anchors' => [
                (string) (0 * sin(2./5. * pi())) => new Anchor(
                    sin(0./5. * pi()), cos(0./5. * pi()),
                    sin(4./5. * pi()) - sin(0./5. * pi()), cos(4./5. * pi()) - cos(0./5. * pi())
                ),
                (string) (2 * sin(2./5. * pi())) => new Anchor(
                    sin(4./5. * pi()), cos(4./5. * pi()),
                    sin(4./5. * pi()) - sin(0./5. * pi()), cos(4./5. * pi()) - cos(0./5. * pi())
                ),
                (string) (4 * sin(2./5. * pi())) => new Anchor(
                    sin(8./5. * pi()), cos(8./5. * pi()),
                    sin(8./5. * pi()) - sin(4./5. * pi()), cos(8./5. * pi()) - cos(4./5. * pi())
                ),
                (string) (6 * sin(2./5. * pi())) => new Anchor(
                    sin(2./5. * pi()), cos(2./5. * pi()),
                    sin(2./5. * pi()) - sin(8./5. * pi()), cos(2./5. * pi()) - cos(8./5. * pi())
                ),
                (string) (8 * sin(2./5. * pi())) => new Anchor(
                    sin(6./5. * pi()), cos(6./5. * pi()),
                    sin(6./5. * pi()) - sin(2./5. * pi()), cos(6./5. * pi()) - cos(2./5. * pi())
                ),
                (string) (10 * sin(2./5. * pi())) => new Anchor(
                    sin(0./5. * pi()), cos(0./5. * pi()),
                    sin(0./5. * pi()) - sin(6./5. * pi()), cos(0./5. * pi()) - cos(6./5. * pi())
                ),
    
                '-1' => null,
                '1.' => new Anchor(
                    0.30901699437494745, 0.048943483704846469,
                    sin(4./5. * pi()) - sin(0./5. * pi()), cos(4./5. * pi()) - cos(0./5. * pi())
                ),
                '2.' => new Anchor(
                    0.50859303213020501, -0.75148047853989586,
                    sin(8./5. * pi()) - sin(4./5. * pi()), cos(8./5. * pi()) - cos(4./5. * pi())
                ),
                '3.' => new Anchor(
                    -0.30042396224474255, -0.16369522624742294,
                    sin(8./5. * pi()) - sin(4./5. * pi()), cos(8./5. * pi()) - cos(4./5. * pi())
                ),
                '4.' => new Anchor(
                    -0.75528258147576777, 0.30901699437494723,
                    sin(2./5. * pi()) - sin(8./5. * pi()), cos(2./5. * pi()) - cos(8./5. * pi())
                ),
                '5.' => new Anchor(
                    0.24471741852423201, 0.3090169943749474,
                    sin(2./5. * pi()) - sin(8./5. * pi()), cos(2./5. * pi()) - cos(8./5. * pi())
                ),
                '6.' => new Anchor(
                    0.71347985580834949, 0.13640744686979345,
                    sin(6./5. * pi()) - sin(2./5. * pi()), cos(6./5. * pi()) - cos(2./5. * pi())
                ),
                '7.' => new Anchor(
                    -0.095537138566597846, -0.45137780542267958,
                    sin(6./5. * pi()) - sin(2./5. * pi()), cos(6./5. * pi()) - cos(2./5. * pi())
                ),
                '8.' => new Anchor(
                    -0.46679030646278635, -0.43663284151350923,
                    sin(0./5. * pi()) - sin(6./5. * pi()), cos(0./5. * pi()) - cos(6./5. * pi())
                ),
                '9.' => new Anchor(
                    -0.15777331208783901, 0.5144236747816443,
                    sin(0./5. * pi()) - sin(6./5. * pi()), cos(0./5. * pi()) - cos(6./5. * pi())
                ),
                '10.' => null,
            ],
        ];
    
        $s = 4. * (sqrt(2.) - 1.) / 3.;
        $sq2 = sqrt(2) / 2.;
        $data['unit circle'] = [
            'path' => (new Path(0, 1))
                ->curveTo($s, 1, 1, $s, 1, 0)
                ->curveTo(1, -$s, $s, -1, 0, -1)
                ->curveTo(-$s, -1, -1, -$s, -1, 0)
                ->curveTo(-1, $s, -$s, 1, 0, 1)
                ->close(),
            'length' => 6.2840021001429731, // ~ 2 * pi() == 6.2831853071795862
            'anchors' => [
                // 0.00 * pi() = 0°
                '0.' => new Anchor(0, 1, 1, 0),
                // 0.25 * pi() = 45°
                '0.7855002625178716' => new Anchor($sq2, $sq2, $sq2, -$sq2),
                // 0.50 * pi() = 90°
                '1.5710005250357433' => new Anchor(1, 0, 0, -1),
                // 0.75 * pi() = 135°
                '2.3565007875535149' => new Anchor($sq2, -$sq2, -$sq2, -$sq2),
                // 1.00 * pi() = 180°
                '3.1420010500714865' => new Anchor(0, -1, -1, 0),
                // 1.25 * pi() = 225°
                '3.9275013125893581' => new Anchor(-$sq2, -$sq2, -$sq2, $sq2),
                // 1.50 * pi() = 270°
                '4.7130015751072298' => new Anchor(-1, 0, 0, 1),
                // 1.75 * pi() = 315°
                '5.4985018376251015' => new Anchor(-$sq2, $sq2, $sq2, $sq2),
                // 2.00 * pi() = 360°
                '6.2840021001429731' => new Anchor(0, 1, 1, 0),
    
                '-1' => null,
                '1' => new Anchor(0.84151421833560425, 0.54046033022001172, 0.54118596004872244, -0.84090294127571141),
                '2' => new Anchor(0.90963208788927608, -0.41599975471530276, -0.41645921390691554, -0.90915440006196646),
                '3' => new Anchor(0.14154149404297445, -0.99006031623007928, -0.99011176488740082, -0.14028076500916395),
                '4' => new Anchor(-0.75648090116724676, -0.65404041466181995, -0.65435982482563892, 0.75618332410478295),
                '5' => new Anchor(-0.9593573919626035, 0.28311869680160873, 0.28263337997097882, 0.95922800862265289),
                '6' => new Anchor(-0.28024439479709762, 0.96019958233812464, 0.96007688862124552, 0.27973624708884021),
                '7' => null,
            ],
        ];
    
        return $data;
    }
    
    /**
     * @param Path $path
     * @param float $length
     * @param Anchor[] $anchors
     * 
     * @dataProvider anchorPathProvider
     */
    public function testAnchorPath(Path $path, $length, array $anchors)
    {
        $anchorPath = new AnchorPath($path);
        $this->assertSame($length == 0, $anchorPath->isEmpty());
        $this->assertSame($length, $anchorPath->getLen());
        $a = [];
        foreach ($anchors as $pos => $anchor) {
            $a[$pos] = $anchorPath->getAnchor((float) $pos);
        }
        $this->assertEquals($anchors, $a);
    }
}
