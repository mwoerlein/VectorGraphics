<?php
namespace VectorGraphics\Model\Style;

class HtmlColor
{
    // TODO: cleanup exceptions
    const PATTERN_NAME = "/^[a-z]*$/";
    const PATTERN_HEX = "/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/";
    const PATTERN_RGB = "/^rgb\\(\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*\\)$/";
    const PATTERN_RGBA = "/^rgba\\(\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*(0|[1-9]\\d?|1\\d\\d?|2[0-4]\\d|25[0-5])\\s*,\\s*((0.[0-9]*)|[01])\\s*\\)$/";
    
    // not used, yet
    const PATTERN_HSL = "/^hsl\\(\\s*(0|[1-9]\\d?|[12]\\d\\d|3[0-5]\\d)\\s*,\\s*((0|[1-9]\\d?|100)%)\\s*,\\s*((0|[1-9]\\d?|100)%)\\s*\\)$/";
    
    /**
     * @param int $r
     * @param int $g
     * @param int $b
     *
     * @return HtmlColor
     */
    public static function rgb($r, $g, $b) {
        //String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
        $hex = "#";
        $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
        return new HtmlColor($hex);
    }
    
    /**
     * @param $hex
     *
     * @return HtmlColor
     * @throws \Exception
     */
    public static function byHex($hex) {
        switch (strlen($hex)) {
            case 4:
                return self::rgb(
                    hexdec(substr($hex,1,1).substr($hex,1,1)),
                    hexdec(substr($hex,2,1).substr($hex,2,1)),
                    hexdec(substr($hex,3,1).substr($hex,3,1))
                );
            case 7:
                $ret = self::rgb(
                    hexdec(substr($hex,1,2)),
                    hexdec(substr($hex,3,2)),
                    hexdec(substr($hex,5,2))
                );
                return $ret;
            default:
            // TODO: cleanup exceptions
            throw new \Exception('Invalid Color: ' . $hex);
        }
    }
    
    /**
     * @param $name
     *
     * @return HtmlColor
     */
    public static function byName($name) {
        return new HtmlColor($name);
    }
    
    /** @var string */
    private $color;
    
    /**
     * @param string $color
     */
    private function __construct($color)
    {
        $this->color = $color;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->color;
    }
}
