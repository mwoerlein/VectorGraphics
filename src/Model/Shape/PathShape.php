<?php
namespace VectorGraphics\Model\Shape;

use VectorGraphics\Model\Path;
use VectorGraphics\Model\Shape;

class PathShape extends Shape
{
    /** @var Path */
    private $path;
    
    /**
     * @param Path $path
     */
    public function __construct(Path $path)
    {
        $this->path = $path;
    }
    
    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }
}