<?php

namespace Coords;

require __DIR__ . '/../../vendor/autoload.php';

use Coords\Distance;

class DistanceTest extends \PHPUnit_Framework_TestCase{

    public function testHaversineGreatCircleDistance(){
        $this->assertEquals(283.79512635244043, Distance::haversineGreatCircleDistance(30.5702, 104.06476, 30.57226, 104.06651));
        $this->assertEquals(2621.240181094488, Distance::haversineGreatCircleDistance(30.5702, 104.06476, 30.54667, 104.06642));
    }

    public function testVincentyGreatCircleDistance(){
        $this->assertEquals(283.79512635244043, Distance::vincentyGreatCircleDistance(30.5702, 104.06476, 30.57226, 104.06651));
        $this->assertEquals(2621.240181094488, Distance::vincentyGreatCircleDistance(30.5702, 104.06476, 30.54667, 104.06642));
    }
}