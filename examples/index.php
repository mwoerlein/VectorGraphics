<?php
require_once __DIR__.'/../vendor/autoload.php';

use VectorGraphics\IO\SVG\SVGWriter;
use VectorGraphics\IO\ZF\PDFWriter;
use VectorGraphics\Model\Graphic;
use ZendPdf\PdfDocument;

$name = isset($_GET['name']) ? $_GET['name'] : 'sample';
$type = isset($_GET['type']) ? $_GET['type'] : 'svg';
if (!file_exists(__DIR__."/$name.php")) {
    die ("Unknown sample: $name");
}
$graphic = require __DIR__."/$name.php";
if (!$graphic instanceof Graphic) {
    die ("Invalid sample: $name");
}

switch ($type) {
    case 'svg':
        header('Content-Type: image/svg+xml');
        echo (new SVGWriter())->toSVG($graphic, 15, 15);
        break;
    case 'pdf':
        $document = new PdfDocument();
        $document->pages[] = $page = $document->newPage("A4");
        (new PDFWriter())->drawGraphic($page, $graphic, 50, 400, 400, 400);

        header('Content-Type: application/pdf');
        $tmp = tempnam(sys_get_temp_dir(), 'vector-graphics');
        $document->save($tmp);
        readfile($tmp);
        unlink($tmp);
        break;
    default:
        die ("Unknown type: $type - (allowed: svg, pdf)");
}
