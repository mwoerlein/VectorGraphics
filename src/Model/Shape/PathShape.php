<?php
namespace VectorGraphics\Model\Shape;

use VectorGraphics\Model\Path;

class PathShape extends AbstractShape
{
    /** @var Path */
    private $path;
    
    /**
     * @param Path $path
     */
    public function __construct(Path $path)
    {
        parent::__construct();
        $this->path = $path;
    }
    
    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * @return bool
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getPath()->isVisible();
    }
}
