h<?php
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Path;

$graphic = new Graphic();
$graphic->setViewportCorners(-50, -50, 50, 50);

$graphic->addRectangle(-49, -49, 98, 98)->setStrokeWidth(2);
$graphic->addCircle(0, 0, 45)->setFillColor('red', 0.5);

$radius = 40;
$path = new Path($radius * sin(0./5. * pi()), $radius * cos(0./5. * pi()));
$path->lineTo($radius * sin(4./5. * pi()), $radius * cos(4./5. * pi()));
$path->lineTo($radius * sin(8./5. * pi()), $radius * cos(8./5. * pi()));
$path->lineTo($radius * sin(2./5. * pi()), $radius * cos(2./5. * pi()));
$path->lineTo($radius * sin(6./5. * pi()), $radius * cos(6./5. * pi()));
$path->close();
$graphic->addPath($path);

return $graphic;
