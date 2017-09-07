<?php

namespace Coords;

require __DIR__ . '/../../vendor/autoload.php';

use Coords\Transform;

class TransformTest extends \PHPUnit_Framework_TestCase
{
    public function testGcj02ToBd09(){
        $this->assertEquals(['longitude' => '116.41036949371029', 'latitude' => '39.92133699351021'], Transform::gcj02ToBd09(116.404, 39.915));
    }

    public function testBd09ToGcj02(){
        $this->assertEquals(['longitude' => '116.39762729119315', 'latitude' => '39.90865673957631'], Transform::bd09ToGcj02(116.404, 39.915));
    }

    public function testWgs84ToGcj02(){
        $this->assertEquals(['longitude' => '116.41024449916938', 'latitude' => '39.91640428150164'], Transform::wgs84ToGcj02(116.404, 39.915));
    }

    public function testGcj02ToWgs84(){
        $this->assertEquals(['longitude' => '116.39775550083061', 'latitude' => '39.91359571849836'], Transform::gcj02ToWgs84(116.404, 39.915));
    }

    public function testBd09ToWgs84(){
        $this->assertEquals(['longitude' => '116.3913836995125', 'latitude' => '39.907253214522164'], Transform::bd09ToWgs84(116.404, 39.915));
    }

    public function testWgs84ToBd09(){
        $this->assertEquals(['longitude' => '116.41662724378733', 'latitude' => '39.922699552216216'], Transform::wgs84ToBd09(116.404, 39.915));
    }
}