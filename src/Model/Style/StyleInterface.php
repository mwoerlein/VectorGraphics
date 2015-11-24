<?php
namespace VectorGraphics\Model\Style;

interface StyleInterface
{
    /**
     * @return bool
     */
    public function isVisible();
    
    // TODO: define serialization/deserialization
}