<?php
namespace VectorGraphics\Tests\Model\Shape;

use VectorGraphics\Model\Path;
use VectorGraphics\Model\Shape\PathShape;

class PathShapeTest extends AbstractShapeTest
{
    /**
     * @param Path $path
     *
     * @return PathShape
     */
    protected function createShape(Path $path = null)
    {
        return new PathShape($path ?: (new Path())->moveTo(0,0)->lineTo(1,1));
    }
    
    /**
     */
    public function testVisibilityWithEmptyPath()
    {
        $path = new Path();
        $shape = $this->createShape($path);
        $this->assertFalse($shape->isVisible());
    }
    
    /**
     * @return array[]
     */
    public function getPathProvider()
    {
        $path = new Path();
        $data = [];
        $data['empty path'] = [
            'shape' => $this->createShape($path),
            'path' => $path,
        ];
        return $data;
    }
}
