<?php

namespace Coords;

class Distance
{
    /** 地球平均半径 */
    const EARTH_RADIUS = '6371000';

    /**
     * 计算大圆上2点的距离，使用半正矢公式
     * @param float $latitudeFrom    开始点的纬度 [deg decimal]
     * @param float $longitudeFrom   开始点的经度 [deg decimal]
     * @param float $latitudeTo      结束点的纬度 [deg decimal]
     * @param float $longitudeTo     结束点的经度 [deg decimal]
     * @param float $earthRadius     地球半径 [米]
     * @return float 2点间的距离(单位和地球半径单位一致，默认为米)
     */
    public static function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = Distance::EARTH_RADIUS){
        // 转换角度为弧度
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    /**
     * 计算大圆上2点的距离，使用文森特公式
     * @param float $latitudeFrom    开始点的纬度 [deg decimal]
     * @param float $longitudeFrom   开始点的经度 [deg decimal]
     * @param float $latitudeTo      结束点的纬度 [deg decimal]
     * @param float $longitudeTo     结束点的经度 [deg decimal]
     * @param float $earthRadius     地球半径 [米]
     * @return float 2点间的距离(单位和地球半径单位一致，默认为米)
     */
    public static function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = Distance::EARTH_RADIUS){
        // 转换角度为弧度
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}