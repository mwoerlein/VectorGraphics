<?php
require_once __DIR__.'/../vendor/autoload.php';

use VectorGraphics\IO\SVG\SVGWriter;

$writer = new SVGWriter();

$name = isset($_GET['name']) ? $_GET['name'] : 'sample';
$callback = 'get' . ucfirst($name) . 'Graphic';
if (method_exists(SVGWriter::class, $callback)) {
    header('Content-Type: image/svg+xml');
    echo $writer->toSVG(SVGWriter::$callback(), 15, 15);    
} else {
    echo "Unknown sample: $name"; 
}
