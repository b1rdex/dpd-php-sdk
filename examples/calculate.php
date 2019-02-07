<?php
require __DIR__ .'/../src/autoload.php';

$options = require __DIR__ .'/config.php';
$config  = new \Ipol\DPD\Config\Config($options);

$shipment = new \Ipol\DPD\Shipment($config);
$shipment->setSender('Беларусь', 'Минская область', 'г. Минск');
$shipment->setReceiver('Беларусь', 'Гродненская область', 'г. Лида');

$shipment->setSelfDelivery(true);
$shipment->setSelfPickup(true);

$shipment->setItems([
    [
        'NAME'       => 'Товар 1',
        'QUANTITY'   => 1,
        'PRICE'      => 1000,
        'VAT_RATE'   => 18,
        'WEIGHT'     => 1000,
        'DIMENSIONS' => [
            'LENGTH' => 200,
            'WIDTH'  => 100,
            'HEIGHT' => 50,
        ]
    ],

    [
        'NAME'       => 'Товар 2',
        'QUANTITY'   => 1,
        'PRICE'      => 1000,
        'VAT_RATE'   => 18,
        'WEIGHT'     => 1000,
        'DIMENSIONS' => [
            'LENGTH' => 350,
            'WIDTH'  => 70,
            'HEIGHT' => 200,
        ]
    ],

    [
        'NAME'       => 'Товар 3',
        'QUANTITY'   => 1,
        'PRICE'      => 1000,
        'VAT_RATE'   => 18,
        'WEIGHT'     => 1000,
        'DIMENSIONS' => [
            'LENGTH' => 220,
            'WIDTH'  => 100,
            'HEIGHT' => 70,
        ]
    ],
], 3000);

$tariff = $shipment->calculator()->calculate();

print_r($tariff);
