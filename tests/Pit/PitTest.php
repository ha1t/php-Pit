<?php

namespace Pit;

class PitTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciate()
    {
        $this->assertInstanceOf('Pit\Pit', new Pit());
    }
}
