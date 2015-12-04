<?php
use VectorGraphics\Model\Graphic;
use VectorGraphics\Model\Path;
use VectorGraphics\Model\Shape\RingArc;
use VectorGraphics\Model\Style\FontStyle;
use VectorGraphics\Model\Style\HtmlColor;
use VectorGraphics\Utils\ArcUtils;

/**
 * @param float $r1
 * @param float $r2
 * @param float $alpha
 * @param float $angle
 *
 * @return Path
 */
function getGroupPath($r1, $r2, $alpha, $angle)
{
    $path = new Path();
    $radians = ArcUtils::getArcRadians($alpha, $angle);
    $scale = ArcUtils::getScale($radians);
    list ($sX, $sY) = ArcUtils::getPolarPoint($r2, $radians[0]);
    list ($curX, $curY) = ArcUtils::getPolarPoint($r1, $radians[0]);
    $path->moveTo($sX, $sY);
    if ($r1 !== $r2) {
        $path->lineTo($curX, $curY);
    }
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
    if ($r1 !== $r2) {
        $path->lineTo($eX, $eY);
    }
    return $path;
}

$graphic = new Graphic();
$graphic->setViewportCorners(-300, -300, 300, 300);
$graphic->getDefaultTextFontStyle()
    ->setName(FontStyle::FONT_HELVETICA)
    ->setHAlign(FontStyle::HORIZONTAL_ALIGN_MIDDLE);

// draw group labels
$graphic->getDefaultShapeStrokeStyle()
    ->setColor('gray');
$graphic->addPath(getGroupPath(275, 280, 1, 118));
$graphic->addPathText('Meine Gruppe', getGroupPath(280, 280, 1, 118));

$graphic->addPath(getGroupPath(275, 280, 121, 118));
$graphic->addPathText('Meine andere Gruppe', getGroupPath(280, 280, 121, 118));

$graphic->addPath(getGroupPath(275, 280, 241, 118));
$graphic->addPathText('Meine dritte Gruppe', getGroupPath(280, 280, 241, 118));

// draw inner arcs
$graphic->getDefaultTextFontStyle()
    ->setVAlign(FontStyle::VERTICAL_ALIGN_CENTRAL);
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
