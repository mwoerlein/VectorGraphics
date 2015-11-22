<?php
require_once __DIR__.'/../vendor/autoload.php';

use VectorGraphics\IO\SVG\SVGWriter;

header('Content-Type: image/svg+xml');
$writer = new SVGWriter();
echo $writer->toSVG(SVGWriter::getSampleGraphic(), 10, 10);