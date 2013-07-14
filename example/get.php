<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Pit\\', array(__DIR__ . '/../src'));


$pit = new \Pit\Pit();
$config = $pit->get('example.com');

var_dump($config);
