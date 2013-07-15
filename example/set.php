<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Pit\\', array(__DIR__ . '/../src'));


$pit = new \Pit\Pit();
$pit->set('example.com', ['data' => [
    'username' => 'user2',
    'password' => 'pass2',
]]);
