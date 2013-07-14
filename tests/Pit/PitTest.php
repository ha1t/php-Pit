<?php

namespace Pit;

class PitTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciate()
    {
        $this->assertInstanceOf('Pit\Pit', new Pit());
    }

    public function testConfig()
    {
        $pit = new Pit();
        $config = $pit->config();
        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('profile', $config);
    }
}
