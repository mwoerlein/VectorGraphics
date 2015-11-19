<?php
namespace VectorGraphics\IO;

use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Shape\Circle;
use VectorGraphics\Model\Shape\PathShape;
use VectorGraphics\Model\Shape\Rectangle;

class AbstractWriter
{

    /**
     * @return Graphic
     *
     * @deprecated just used for manual testing
     */
    public static function getSampleGraphic()
    {
        $graphic = new Graphic();
        $graphic->setViewportCorners(-100, -100, 100, 100);

        $circ = new Circle(-40, 40, 40);
        $circ->setStrokeColor("yellow");
        $graphic->add($circ);
        $circ = new Circle(40, 40, 40);
        $circ->setStrokeColor("yellow");
        $graphic->add($circ);
        $circ = new Circle(-40, -40, 40);
        $circ->setStrokeColor("yellow");
        $graphic->add($circ);
        $circ = new Circle(40, -40, 40);
        $circ->setStrokeColor("yellow");
        $graphic->add($circ);

        $rect = new Rectangle(-20, 0, 20, 15);
        $rect->setFillColor("red");
        $rect->setStrokeColor("green");
        $rect->setOpacity(0.3);
        $graphic->add($rect);
        $rect = new Rectangle(-35, 10, 30, 30);
        $rect->setFillColor("red");
        $rect->setStrokeColor("green");
        $rect->setOpacity(0.7);
        $graphic->add($rect);
        $rect = new Rectangle(0, 0, 30, 30);
        $rect->setFillColor("blue");
        $rect->setStrokeColor(null);
        $graphic->add($rect);
        $rect = new Rectangle(-30, -30, 30, 30);
        $rect->setFillColor("yellow");
        $rect->setStrokeColor("red");
        $graphic->add($rect);
        $rect = new Rectangle(0, -30, 30, 30);
        $rect->setFillColor("green");
        $rect->setStrokeColor("blue");
        $rect->setStrokeOpacity(0.5);
        $graphic->add($rect);

        $radius = 80;
        $path = new Path();
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

        $shape = new PathShape($path);
        $shape->setFillColor("black");
        $shape->setFillOpacity(0.3);

        $graphic->add($shape);

        $radius = 80;
        $path = new Path();
        $path->moveTo($radius * sin(1./5. * pi()), $radius * cos(1./5. * pi()));
        $path->lineTo($radius * sin(5./5. * pi()), $radius * cos(5./5. * pi()));
        $path->lineTo($radius * sin(9./5. * pi()), $radius * cos(9./5. * pi()));
        $path->lineTo($radius * sin(3./5. * pi()), $radius * cos(3./5. * pi()));
        $path->lineTo($radius * sin(7./5. * pi()), $radius * cos(7./5. * pi()));
        $path->lineTo($radius * sin(1./5. * pi()), $radius * cos(1./5. * pi()));

        $shape = new PathShape($path);
        $shape->setStrokeColor("blue");
        $shape->setFillColor("black");
        $shape->setStrokeOpacity(0.6);
        $shape->setOpacity(0.6);

        $graphic->add($shape);

        $circ = new Circle(0, 0, 80);
        $circ->setStrokeColor("green");
        $graphic->add($circ);
        return $graphic;
    }
}
