<?php

namespace Coords;

class Transform
{
    const X_PI = 52.359877559829882898;

    /** 赤道半径(地球长半轴) */
    const EQUATORIAL_RADIUS = 6378245.0;

    /** 地球扁率 */
    const ELLIPTICITY = 0.00669342162296594323;

    /**
     * 火星坐标系(GCJ-02)转百度坐标系(BD-09)
     * 谷歌、高德 -> 百度
     * @param  float     $longitude 火星坐标经度
     * @param  float     $latitude  火星坐标纬度
     * @return array
     * @author maofei
     * @since  2017-09-07
     */
    public static function gcj02ToBd09($longitude, $latitude){
        $z = sqrt($longitude * $longitude + $latitude * $latitude) + 0.00002 * sin($latitude * Transform::X_PI);

        $theta = atan2($latitude, $longitude) + 0.000003 * cos($longitude * Transform::X_PI);

        return [
            'longitude' => $z * cos($theta) + 0.0065,
            'latitude'  => $z * sin($theta) + 0.006,
        ];
    }

    /**
     * 百度坐标系(BD-09)转火星坐标系(GCJ-02)
     * 百度 -> 谷歌、高德
     * @param  float     $longitude 百度坐标经度
     * @param  float     $latitude  百度坐标纬度
     * @return array
     * @author maofei
     * @since  2017-09-07
     */
    public static function bd09ToGcj02($longitude, $latitude){
        $x = $longitude - 0.0065;
        $y = $latitude - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * Transform::X_PI);
        $theta = atan2($y, $x) - 0.000003 * cos($x * Transform::X_PI);
        return [
            'longitude' => $z * cos($theta),
            'latitude'  => $z * sin($theta),
        ];
    }

    /**
     * 判断是否在国内，不在国内不做偏移
     * @param  float     $longitude 坐标经度
     * @param  float     $latitude  坐标纬度
     * @return bool
     * @author maofei
     * @since  2017-09-07
     */
    public static function outOfChina($longitude, $latitude){
        return !($longitude > 73.66 && $longitude < 135.05 && $latitude > 3.86 && $latitude < 53.55);
    }

    private static function _transformLongitude($longitude, $latitude){
        $result = 300.0 + $longitude + 2.0 * $latitude + 0.1 * $longitude * $longitude + 0.1 * $longitude * $latitude + 0.1 * sqrt(abs($longitude));
        $result += (20.0 * sin(6.0 * $longitude * M_PI) + 20.0 * sin(2.0 * $longitude * M_PI)) * 2.0 / 3.0;
        $result += (20.0 * sin($longitude * M_PI) + 40.0 * sin($longitude / 3.0 * M_PI)) * 2.0 / 3.0;
        $result += (150.0 * sin($longitude / 12.0 * M_PI) + 300.0 * sin($longitude / 30.0 * M_PI)) * 2.0 / 3.0;
        return $result;
    }

    private static function _transformLatitude($longitude, $latitude){
        $result = -100.0 + 2.0 * $longitude + 3.0 * $latitude + 0.2 * $latitude * $latitude + 0.1 * $longitude * $latitude + 0.2 * sqrt(abs($longitude));
        $result += (20.0 * sin(6.0 * $longitude * M_PI) + 20.0 * sin(2.0 * $longitude * M_PI)) * 2.0 / 3.0;
        $result += (20.0 * sin($latitude * M_PI) + 40.0 * sin($latitude / 3.0 * M_PI)) * 2.0 / 3.0;
        $result += (160.0 * sin($latitude / 12.0 * M_PI) + 320 * sin($latitude * M_PI / 30.0)) * 2.0 / 3.0;
        return $result;
    }

    /**
     * WGS84转GCJ02(火星坐标系)
     * @param  float     $longitude WGS84坐标系的经度
     * @param  float     $latitude  WGS84坐标系的纬度
     * @return array
     * @author maofei
     * @since  2017-09-07
     */
    public static function wgs84ToGcj02($longitude, $latitude){
        if( Transform::outOfChina($longitude, $latitude) ){
            return [
                'longitude' => $longitude,
                'latitude'  => $latitude,
            ];
        }

        $dLatitude  = Transform::_transformLatitude($longitude - 105.0, $latitude - 35.0);
        $dLongitude = Transform::_transformLongitude($longitude - 105.0, $latitude - 35.0);
        $radiusLatitude = $latitude / 180.0 * M_PI;
        $magic = sin($radiusLatitude);
        $magic = 1 - Transform::ELLIPTICITY * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $dLatitude = ($dLatitude * 180.0) / ((Transform::EQUATORIAL_RADIUS * (1 - Transform::ELLIPTICITY)) / ($magic * $sqrtMagic) * M_PI);
        $dLongitude = ($dLongitude * 180.0) / (Transform::EQUATORIAL_RADIUS / $sqrtMagic * cos($radiusLatitude) * M_PI);
        return [
            'longitude' => $longitude + $dLongitude,
            'latitude'  => $latitude + $dLatitude,
        ];
    }

    /**
     * GCJ02(火星坐标系)转WGS84
     * @param  float     $longitude 火星坐标系的经度
     * @param  float     $latitude  火星坐标系纬度
     * @return array
     * @author maofei
     * @since  2017-09-07
     */
    public static function gcj02ToWgs84($longitude, $latitude){
        if( Transform::outOfChina($longitude, $latitude) ){
            return [
                'longitude' => $longitude,
                'latitude'  => $latitude,
            ];
        }

        $dLatitude  = Transform::_transformLatitude($longitude - 105.0, $latitude - 35.0);
        $dLongitude = Transform::_transformLongitude($longitude - 105.0, $latitude - 35.0);
        $radiusLatitude = $latitude / 180.0 * M_PI;
        $magic = sin($radiusLatitude);
        $magic = 1 - Transform::ELLIPTICITY * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $dLatitude = ($dLatitude * 180.0) / ((Transform::EQUATORIAL_RADIUS * (1 - Transform::ELLIPTICITY)) / ($magic * $sqrtMagic) * M_PI);
        $dLongitude = ($dLongitude * 180.0) / (Transform::EQUATORIAL_RADIUS / $sqrtMagic * cos($radiusLatitude) * M_PI);
        return [
            'longitude' => $longitude * 2 - ($longitude + $dLongitude),
            'latitude'  => $latitude * 2 - ($latitude + $dLatitude),
        ];
    }

    /**
     * 百度坐标系(BD-09)转WGS84
     * @param  float     $longitude 百度坐标经度
     * @param  float     $latitude  百度坐标纬度
     * @return float                [description]
     * @author maofei
     * @since  2017-09-07
     */
    public static function bd09ToWgs84($longitude, $latitude){
        $result = Transform::bd09ToGcj02($longitude, $latitude);
        return Transform::gcj02ToWgs84($result['longitude'], $result['latitude']);
    }

    /**
     * WGS84转百度坐标系(BD-09)
     * @param  float     $longitude WGS84坐标系的经度
     * @param  float     $latitude  WGS84坐标系的纬度
     * @return array
     * @author maofei
     * @since  2017-09-07
     */
    public static function wgs84ToBd09($longitude, $latitude){
        $result = Transform::wgs84ToGcj02($longitude, $latitude);
        return Transform::gcj02ToBd09($result['longitude'], $result['latitude']);
    }
}