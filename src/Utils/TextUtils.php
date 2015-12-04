<?php
namespace VectorGraphics\Utils;

class TextUtils
{
    /**
     * @param string $text
     *
     * @return string[]
     */
    public static function getCharacters($text)
    {
        return mb_split('/(?!^)(?=.)/u', $text);
    }
    
    /**
     * @param string $text
     *
     * @return int[]
     */
    public static function getCodes($text)
    {
        $length = strlen($text);
        $ords = [];
        $i = 0;
        while($i < $length)
        {
            list($ord, $bytes) = self::ordUtf8($text, $i);
            if ($ord !== false) {
                $ords[] = $ord;
            }
            $i += $bytes;
        }
        
        return $ords;
    }
    
    /**
     * code from http://php.net/manual/en/function.ord.php#78032
     *
     * @param string $text
     * @param int $index
     *
     * @return int[] [ord, bytes]
     */
    private static function ordUtf8($text, $index = 0)
    {
        $len = strlen($text);
        $bytes = 0;
        
        $char = false;
        
        if ($index < $len)
        {
            $h = ord($text{$index});
            
            if($h <= 0x7F)
            {
                $bytes = 1;
                $char = $h;
            }
            elseif ($h < 0xC2)
            {
                $char = false;
            }
            elseif ($h <= 0xDF && $index < $len - 1)
            {
                $bytes = 2;
                $char = ($h & 0x1F) <<  6 | (ord($text{$index + 1}) & 0x3F);
            }
            elseif($h <= 0xEF && $index < $len - 2)
            {
                $bytes = 3;
                $char = ($h & 0x0F) << 12 | (ord($text{$index + 1}) & 0x3F) << 6
                    | (ord($text{$index + 2}) & 0x3F);
            }
            elseif($h <= 0xF4 && $index < $len - 3)
            {
                $bytes = 4;
                $char = ($h & 0x0F) << 18 | (ord($text{$index + 1}) & 0x3F) << 12
                    | (ord($text{$index + 2}) & 0x3F) << 6
                    | (ord($text{$index + 3}) & 0x3F);
            }
        }
        
        return array($char, $bytes);
    }
}
