[![Build Status](https://travis-ci.org/mwoerlein/vector-graphics.svg?branch=develop)](https://travis-ci.org/mwoerlein/vector-graphics)

# Vector Graphics Library
PHP library for OO-modeling of vector graphics.

## Objectives
This library is designed to construct and describe general vector graphics independent of its application backend.
These descriptions are intended to separate the definition on an graphic/chart/... and its representation.

A graphic could be serialized into various document types using there capabilities for vecorized representations.
Currently supported/planed serializations are:

- export as standalone SVG content
- serialize into a Zend-PDF page
- serialize into an Imagine image

## Installation

To add vector-graphics as a local, per-project dependency to your project, simply add a dependency on `mwoerlein/vector-graphics` to your project's `composer.json` file. Here is a minimal example of a `composer.json` file:

```JSON
{
    "require": {
        "mwoerlein/vector-graphics": "*@dev"
    }
}
```

## Example Usage
The following example generates a svg-image containing a pentagram in a red circle inside of a square
 
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
    
    header('Content-Type: image/svg+xml');
    echo (new SVGWriter())->toSVG($graphic, 10, 10);

[![SVG Sample](https://raw.githubusercontent.com/mwoerlein/vector-graphics/develop/docs/pentagram.png)](https://github.com/mwoerlein/vector-graphics/blob/develop/docs/pentagram.svg)

## Progress

see in [ChangeLog](https://raw.githubusercontent.com/mwoerlein/vector-graphics/develop/CHANGELOG.md)
