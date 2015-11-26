<?php
namespace VectorGraphics\Tests\Model\Style;

use PHPUnit_Framework_TestCase as TestCase;
use VectorGraphics\Model\Style\HtmlColor;

/**
 * @covers VectorGraphics\Model\Style\HtmlColor
 */
class HtmlColorTest extends TestCase
{
    /**
     */
    public function testByName()
    {
        $this->assertSame('black', HtmlColor::byName('black')->__toString());
        $this->assertSame('green', HtmlColor::byName('green')->__toString());
    }
    
    /**
     */
    public function testByHex()
    {
        $this->assertSame('#000000', HtmlColor::byHex('#000')->__toString());
        $this->assertSame('#112233', HtmlColor::byHex('#123')->__toString());
        $this->assertSame('#123abc', HtmlColor::byHex('#123abc')->__toString());
        $this->assertSame('#123abc', HtmlColor::byHex('#123ABC')->__toString());
        
        $this->setExpectedException(\Exception::class);
        HtmlColor::byHex('no hex string');
    }
    
    /**
     */
    public function testRgb()
    {
        $this->assertSame('#000000', HtmlColor::rgb(0, 0, 0)->__toString());
        $this->assertSame('#112233', HtmlColor::rgb(17, 34, 51)->__toString());
        $this->assertSame('#ffffff', HtmlColor::rgb(255,255,255)->__toString());
    }
}
