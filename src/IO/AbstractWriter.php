<?php
namespace VectorGraphics\IO;

use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Shape\RingArc;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\HtmlColor;
use VectorGraphics\Utils\ArcUtils;

class AbstractWriter
{
    /**
     * @return Graphic
     *
     * @deprecated just used for manual testing
     */
    public static function getReadmeGraphic()
    {
        $graphic = new Graphic();
        $graphic->setViewportCorners(-50, -50, 50, 50);
        
        $graphic->addRectangle(-49, -49, 98, 98)->setStrokeWidth(2);
        $graphic->addCircle(0, 0, 45)->setFillColor('red', 0.5);
        
        $radius = 40;
        $path = new Path();
        $path->moveTo($radius * sin(0./5. * pi()), $radius * cos(0./5. * pi()));
        $path->lineTo($radius * sin(4./5. * pi()), $radius * cos(4./5. * pi()));
        $path->lineTo($radius * sin(8./5. * pi()), $radius * cos(8./5. * pi()));
        $path->lineTo($radius * sin(2./5. * pi()), $radius * cos(2./5. * pi()));
        $path->lineTo($radius * sin(6./5. * pi()), $radius * cos(6./5. * pi()));
        $path->close();
        $graphic->addPath($path);
        
        return $graphic;
    }
    
    /**
     * @return Graphic
     *
     * @deprecated just used for manual testing
     */
    public static function getSampleGraphic()
    {
        $graphic = new Graphic();
        $graphic->setViewportCorners(-100, -100, 100, 100);
        
        $graphic->addCircle(-40, 40, 40)
            ->setStrokeColor('yellow');
        
        $text = $graphic->addText('A', -40, 40);
        $text->align(FontStyle::HORIZONTAL_ALIGN_RIGHT, FontStyle::VERTICAL_ALIGN_BASE);
        $text->setStrokeColor('red');
        $text->setStrokeWidth(1);
        $text->setFillOpacity(0.4);
        $text->setFontSize(80);
        
        $graphic->addCircle(40, 40, 40)
            ->setStrokeColor('yellow');
        
        $text = $graphic->addText('B', 40, 40);
        $text->setFontName(FontStyle::FONT_COURIER);
        $text->align(FontStyle::HORIZONTAL_ALIGN_LEFT, FontStyle::VERTICAL_ALIGN_BOTTOM);
        $text->setFillColor('blue');
        $text->setStrokeColor('red', 0.4);
        $text->setStrokeWidth(1);
        $text->setFontSize(80);
        
        $graphic->addCircle(-40, -40, 40)
            ->setStrokeColor('yellow');
        
        $graphic->addText("C\nh\nj i", -40, -40);
        
        $graphic->addCircle(40, -40, 40)
            ->setStrokeColor('yellow');
        
        $text = $graphic->addText('DaDa', 40, -40);
        $text->setFontName(FontStyle::FONT_HELVETICA);
        $text->setRotation(45);
        $text->align(FontStyle::HORIZONTAL_ALIGN_MIDDLE, FontStyle::VERTICAL_ALIGN_CENTRAL);
        $text->setStrokeColor('red');
        $text->setStrokeWidth(1);
        $text->setOpacity(0.4);
        $text->setFontSize(60);
        
        $shape = $graphic->addPath((new Path())
            ->moveTo(0, 0)
            ->lineTo(80, -80)
            ->moveTo(0, -80)
            ->lineTo(80, 0)
        );
        $shape->setStrokeColor('black');
        $shape->setFillColor(null);
        $shape->setOpacity(0.6);
        
        $rect = $graphic->addRectangle(-20, 0, 20, 15);
        $rect->setFillColor('red');
        $rect->setStrokeColor('green');
        $rect->setOpacity(0.3);
        
        $rect = $graphic->addRectangle(-35, 10, 30, 30);
        $rect->setFillColor('red');
        $rect->setStrokeColor('green');
        $rect->setOpacity(0.7);

        $rect = $graphic->addRectangle(0, 0, 30, 30);
        $rect->setFillColor('blue');
        $rect->setStrokeColor(null);
        
        $rect = $graphic->addRectangle(-30, -30, 30, 30);
        $rect->setFillColor('yellow');
        $rect->setStrokeColor('red');
        
        $rect = $graphic->addRectangle(0, -30, 30, 30);
        $rect->setFillColor('green');
        $rect->setStrokeColor('blue', 0.5);
        
        $radius = 80;
        $p1 = $path = new Path();
        $path->moveTo($radius * sin(0./5. * pi()), $radius * cos(0./5. * pi()));
        $path->curveTo(
            $radius * sin(1./5. * pi())/2, $radius * cos(1./5. * pi())/2,
            $radius * sin(3./5. * pi())/2, $radius * cos(3./5. * pi())/2,
            $radius * sin(4./5. * pi()), $radius * cos(4./5. * pi())
        );
        $path->curveTo(
            $radius * sin(5./5. * pi())/2, $radius * cos(5./5. * pi())/2,
            $radius * sin(7./5. * pi())/2, $radius * cos(7./5. * pi())/2,
            $radius * sin(8./5. * pi()), $radius * cos(8./5. * pi())
        );
        $path->curveTo(
            $radius * sin(9./5. * pi())/2, $radius * cos(9./5. * pi())/2,
            $radius * sin(1./5. * pi())/2, $radius * cos(1./5. * pi())/2,
            $radius * sin(2./5. * pi()), $radius * cos(2./5. * pi())
        );
        $path->curveTo(
            $radius * sin(3./5. * pi())/2, $radius * cos(3./5. * pi())/2,
            $radius * sin(5./5. * pi())/2, $radius * cos(5./5. * pi())/2,
            $radius * sin(6./5. * pi()), $radius * cos(6./5. * pi())
        );
        $path->curveTo(
            $radius * sin(7./5. * pi())/2, $radius * cos(7./5. * pi())/2,
            $radius * sin(9./5. * pi())/2, $radius * cos(9./5. * pi())/2,
            $radius * sin(0./5. * pi()), $radius * cos(0./5. * pi())
        );
        $path->close();
    
        $graphic->addPath($path)
            ->setFillColor('black', 0.3);
        
        $radius = 80;
        $path = new Path();
        $path->moveTo($radius * sin(1./5. * pi()), $radius * cos(1./5. * pi()));
        $path->lineTo($radius * sin(5./5. * pi()), $radius * cos(5./5. * pi()));
        $path->lineTo($radius * sin(9./5. * pi()), $radius * cos(9./5. * pi()));
        $path->lineTo($radius * sin(3./5. * pi()), $radius * cos(3./5. * pi()));
        $path->lineTo($radius * sin(7./5. * pi()), $radius * cos(7./5. * pi()));
        $path->lineTo($radius * sin(1./5. * pi()), $radius * cos(1./5. * pi()));
        $path->close();
    
        $graphic->addPath($path)
            ->setFillColor('black')
            ->setStrokeColor('blue', 0.6)
            ->setOpacity(0.6);
        
        $radius = 20;
        $path = new Path();
        $path->moveTo($radius * sin(0./3. * pi()), $radius * cos(0./3. * pi()));
        $path->lineTo($radius * sin(2./3. * pi()), $radius * cos(2./3. * pi()));
        $path->lineTo($radius * sin(4./3. * pi()), $radius * cos(4./3. * pi()));
        $path->close();
        $path->moveTo($radius * sin(1./3. * pi()), $radius * cos(1./3. * pi()));
        $path->lineTo($radius * sin(5./3. * pi()), $radius * cos(5./3. * pi()));
        $path->lineTo($radius * sin(3./3. * pi()), $radius * cos(3./3. * pi()));
        $path->close();
    
        $graphic->addPath($path)
            ->setFillColor('gray')
            ->setStrokeColor('black', 0.6)
            ->setOpacity(0.6);
    
        $graphic->addCircle(0, 0, 80)
            ->setStrokeColor('green');
        
        // just supported in svg-writer, yet
        $text = $graphic->addPathText('Round and Round and Round and Round ...', $p1);
        $text->align(FontStyle::HORIZONTAL_ALIGN_MIDDLE, FontStyle::VERTICAL_ALIGN_BOTTOM);
        $text->setStrokeColor('red');
        $text->setStrokeWidth(.2);
        $text->setOpacity(0.4);
        $text->setFontSize(12);
        
        return $graphic;
    }
    
    /**
     * @return Graphic
     *
     * @deprecated just used for manual testing
     */
    public static function getSunburstGraphic()
    {
        $graphic = new Graphic();
        $graphic->setViewportCorners(-300, -300, 300, 300);
        $graphic->getDefaultTextFontStyle()
            ->setName(FontStyle::FONT_HELVETICA);
    
        // draw group labels
        $graphic->getDefaultTextFontStyle()
            ->align(FontStyle::HORIZONTAL_ALIGN_MIDDLE);
        $graphic->getDefaultShapeStrokeStyle()
            ->setColor('gray');
        $graphic->addPath(self::getGroupPath(275, 280, 1, 118));
        $graphic->addPathText('Meine Gruppe', self::getGroupPath(280, 280, 1, 118));
    
        $graphic->addPath(self::getGroupPath(275, 280, 121, 118));
        $graphic->addPathText('Meine andere Gruppe', self::getGroupPath(280, 280, 121, 118));
    
        $graphic->addPath(self::getGroupPath(275, 280, 241, 118));
        $graphic->addPathText('Meine dritte Gruppe', self::getGroupPath(280, 280, 241, 118));

        // draw inner arcs
        $graphic->getDefaultTextFontStyle()
            ->align(FontStyle::HORIZONTAL_ALIGN_MIDDLE, FontStyle::VERTICAL_ALIGN_CENTRAL);
        $graphic->getDefaultTextFillStyle()
            ->setColor('white');
        $graphic->getDefaultShapeFillStyle()
            ->setColor(HtmlColor::rgb(236, 88, 85));
        $graphic->getDefaultShapeStrokeStyle()
            ->setColor('white');
        
        for($i=-4; $i<25; $i++) {
            $arc = $graphic->addRingArc(0, 0, 6 * $i + 50, 240, 15 * $i, 15);
            $arc->setFillOpacity(($i+8)/32.);
            list ($x, $y) = $arc->getPoint(RingArc::ALPHA_CENTRAL, RingArc::RADIUS_MIDDLE);
            $graphic->addText($i, $x, $y);
        }
    
        // draw outer arcs
        $graphic->addRingArc(0, 0, 240, 270, 0, 240)
            ->setFillColor('rgb(81, 166, 74)');
        $graphic->addRingArc(0, 0, 240, 270, 330, -30)
            ->setFillColor('rgb(107, 178, 241)');
        $graphic->addRingArc(0, 0, 240, 270, 330, 30)
            ->setFillColor('rgb(69, 70, 77)', 0.3);
        
        return $graphic;
    }
    
    /**
     * @param float $r1
     * @param float $r2
     * @param float $alpha
     * @param float $angle
     *
     * @return Path
     */
    private static function getGroupPath($r1, $r2, $alpha, $angle)
    {
        $path = new Path();
        $radians = ArcUtils::getArcRadians($alpha, $angle);
        $scale = ArcUtils::getScale($radians);
        list ($sX, $sY) = ArcUtils::getPolarPoint($r2, $radians[0]);
        list ($curX, $curY) = ArcUtils::getPolarPoint($r1, $radians[0]);
        $path
            ->moveTo($sX, $sY)
            ->lineTo($curX, $curY);
        $pos = 1;
        while ($pos < count($radians)) {
            list ($nextX, $nextY) = ArcUtils::getPolarPoint($r1, $radians[$pos++]);
            list ($c1X, $c1Y) = ArcUtils::getBezierControl($curX, $curY, -$scale);
            list ($c2X, $c2Y) = ArcUtils::getBezierControl($nextX, $nextY, $scale);
            $path->curveTo($c1X, $c1Y, $c2X, $c2Y, $nextX, $nextY);
            $curX = $nextX;
            $curY = $nextY;
        }
        list ($eX, $eY) = ArcUtils::getPolarPoint($r2, end($radians));
        $path->lineTo($eX, $eY);
        return $path;
    }
}
