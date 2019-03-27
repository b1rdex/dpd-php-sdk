<?php

namespace Ipol\DPD\API\Service;

use Ipol\DPD\API\Client\Factory as ClientFactory;
use Ipol\DPD\API\User\UserInterface;

/**
 * Служба для работы со статусами заказа
 */
class Tracking11 implements ServiceInterface
{
    protected $wsdl = 'http://ws.dpd.ru/services/tracing1-1?wsdl';
    /**
     * @var \Ipol\DPD\API\Client\ClientInterface
     */
    private $client;

    /**
     * Конструктор класса
     *
     * @param \Ipol\DPD\API\User\UserInterface
     */
    public function __construct(UserInterface $user)
    {
        $this->client = ClientFactory::create($this->wsdl, $user);
        $this->client->setCacheTime(0);
    }

    /**
     * Получить историю состояний всех посылок заданного заказа.
     * Заказ идентифицируется по номеру заказа в информационной системе DPD.
     *
     * @param string   $dpdOrderNr Номер заказа в информационной системе DPD
     * @param int|null $pickupYear Год заказа (т.к. номера заказов DPD уникальные в пределах года, требуется уточнение, чтобы получить однозначный результат)
     *
     * @return array
     *
     * @throws \SoapFault
     */
    public function getStatesByDPDOrder($dpdOrderNr, $pickupYear = null)
    {
        return $this->client->invoke('getStatesByClient', compact($dpdOrderNr, $pickupYear));
    }
}
