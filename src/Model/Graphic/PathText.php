<?php
namespace VectorGraphics\Model\Graphic;

use VectorGraphics\Model\Path;

class PathText extends AbstractText
{
    /** @var Path */
    public $path = null;
    
    /**
     * @param string $text
     * @param Path $path
     */
    public function __construct($text, Path $path)
    {
        parent::__construct($text);
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
