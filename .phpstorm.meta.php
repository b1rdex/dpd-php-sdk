<?php

namespace PHPSTORM_META {

    override(\Ipol\DPD\API\User\UserInterface::getService(0), map([
        'geography' => \Ipol\DPD\API\Service\Geography::class,
        'calculator' => \Ipol\DPD\API\Service\Calculator::class,
        'order' => \Ipol\DPD\API\Service\Order::class,
        'label-print' => \Ipol\DPD\API\Service\LabelPrint::class,
        'tracking' => \Ipol\DPD\API\Service\Tracking::class,
        'tracking11' => \Ipol\DPD\API\Service\Tracking11::class,
        'tracking-order' => \Ipol\DPD\API\Service\TrackingOrder::class,
        'event-tracking' => \Ipol\DPD\API\Service\EventTracking::class,
    ]));

    override(\Ipol\DPD\DB\ConnectionInterface::getTable(0), map([
        'location' => \Ipol\DPD\DB\Location\Table::class,
        'terminal' => \Ipol\DPD\DB\Terminal\Table::class,
        'order' => \Ipol\DPD\DB\Order\Table::class,
    ]));
}
