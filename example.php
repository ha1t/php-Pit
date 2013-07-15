<?php
/**
 *
 *
 */
require_once 'Pit.php';
$pit = new Pit();
$re = $pit->get('test3', array('require' =>
    array(
        'mail' => 'your mail',
        'pass' => 'your pass'
    )
));
var_dump($re);
