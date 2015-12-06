<?php
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Style\FontStyle;

$graphic = new Graphic();
$graphic->setViewportCorners(-100, -100, 100, 100);

$graphic->addCircle(-40, 40, 40)
    ->setStrokeColor('yellow');

$text = $graphic->addText('A', -40, 40);
$text->setFont(80);
$text->align(FontStyle::HORIZONTAL_ALIGN_RIGHT, FontStyle::VERTICAL_ALIGN_BASE);
$text->setStrokeColor('red');
$text->setStrokeWidth(1);
$text->setFillOpacity(0.4);

$graphic->addCircle(40, 40, 40)
    ->setStrokeColor('yellow');

$text = $graphic->addText('B', 40, 40);
$text->setFont(80, FontStyle::FONT_COURIER);
$text->align(FontStyle::HORIZONTAL_ALIGN_LEFT, FontStyle::VERTICAL_ALIGN_BOTTOM);
$text->setFillColor('blue');
$text->setStrokeColor('red', 0.4);
$text->setStrokeWidth(1);

$graphic->addCircle(-40, -40, 40)
    ->setStrokeColor('yellow');

$graphic->addText("C\nh\nj i", -40, -40);

$graphic->addCircle(40, -40, 40)
    ->setStrokeColor('yellow');

$text = $graphic->addText('DaDa', 40, -40);
$text->setRotation(45);
$text->setFont(60, FontStyle::FONT_HELVETICA);
$text->align(FontStyle::HORIZONTAL_ALIGN_MIDDLE, FontStyle::VERTICAL_ALIGN_CENTRAL);
$text->setStrokeColor('red');
$text->setStrokeWidth(1);
$text->setOpacity(0.4);

$shape = $graphic->addPath((new Path(0, 0))
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
$p1 = $path = new Path($radius * sin(0./5. * pi()), $radius * cos(0./5. * pi()));
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
$path = new Path($radius * sin(1./5. * pi()), $radius * cos(1./5. * pi()));
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
$path = new Path($radius * sin(0./3. * pi()), $radius * cos(0./3. * pi()));
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
$text->setFont(12);
$text->align(FontStyle::HORIZONTAL_ALIGN_MIDDLE, FontStyle::VERTICAL_ALIGN_BOTTOM);
$text->setStrokeColor('red');
$text->setStrokeWidth(.2);
$text->setOpacity(0.4);

return $graphic;
