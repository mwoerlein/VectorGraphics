<?php
namespace VectorGraphics\Utils;

class ArcUtils
{
    /**
     * @param float $alpha
     *
     * @return float
     */
    public static function toRadian($alpha) {
        return $alpha / 180. * pi();
    }
    
    /**
     * @param float $radius
     * @param float $radian
     *
     * @return float[] [x, y]
     */
    public static function getPolarPoint($radius, $radian)
    {
        return [$radius * sin($radian), $radius * cos($radian)];
    }
    
    /**
     * @param float $x
     * @param float $y
     * @param float $scale
     *
     * @return float[] [x, y]
     */
    public static function getBezierControl($x, $y, $scale)
    {
        return [$x - $scale * $y, $y + $scale * $x];
    }
    
    /**
     * @param float $alpha
     * @param float $angle
     *
     * @return float[]
     */
    public static function getArcRadians($alpha, $angle)
    {
        $start = self::toRadian($alpha);
        $end = self::toRadian($alpha + $angle);
        $stepCount = ceil($angle / 90.);
        $step = ($end - $start) / $stepCount;
        $radian = $start;
        $radians = [];
        while($stepCount-- > 0) {
            $radians[] = $radian;
            $radian += $step;
        }
        $radians[] = $end;
        return $radians;
    }
    
    /**
     * @param float[] $radians
     *
     * @return float
     */
    public static function getScale(array $radians)
    {
        return (4./3.) * tan(($radians[1] - $radians[0]) / 4.);
    }
}
