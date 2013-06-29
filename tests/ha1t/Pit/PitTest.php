<?php

namespace ha1t\Pit;

class PitTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciate()
    {
        $this->assertInstanceOf('ha1t\\Pit\\Pit', new Pit());
    }
}
