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

    public function testLoad()
    {
        $pit = new Pit();
        $profile = $pit->load();
        $this->assertInternalType('array', $profile);
    }

    public function testSwitchProfile()
    {
        $pit = new Pit();
        $pit->switchProfile('test');
        $pit->switchProfile('default');
    }

    public function testGet()
    {
        $pit = new Pit();
        $pit->switchProfile('test');

        $config = $pit->get('example.com');

        $pit->switchProfile('default');
    }
}
