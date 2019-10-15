<?php
require __DIR__ .'/../vendor/autoload.php';

$options = require __DIR__ .'/config.php';
$config  = new \Ipol\DPD\Config\Config($options);

\Ipol\DPD\Agents::checkOrderStatus($config);