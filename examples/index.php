<?php
require_once __DIR__.'/../vendor/autoload.php';

use VectorGraphics\IO\SVG\SVGWriter;

$name = isset($_GET['name']) ? $_GET['name'] : 'sample';
if (file_exists(__DIR__."/$name.php")) {
    header('Content-Type: image/svg+xml');
    echo (new SVGWriter())->toSVG(require __DIR__."/$name.php", 15, 15);    
} else {
    echo "Unknown sample: $name"; 
}
