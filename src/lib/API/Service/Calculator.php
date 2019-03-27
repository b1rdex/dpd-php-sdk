<?php
namespace Ipol\DPD\API\Service;

use \Ipol\DPD\API\User\UserInterface;
use \Ipol\DPD\API\Client\Factory as ClientFactory;

/**
 * Служба расчета стоимости доставки
 */
class Calculator implements ServiceInterface
{
	protected $wdsl = 'http://ws.dpd.ru/services/calculator2?wsdl';
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
		$this->client = ClientFactory::create($this->wdsl, $user);
	}

	/**
	 * Рассчитать общую стоимость доставки по России и странам ТС.
	 * 
	 * @param  array  $params
	 * 
	 * @return array
	 */
	public function getServiceCost(array $params)
	{
		return $this->client->invoke('getServiceCost2', $params, 'request', 'serviceCode');
	}

	/**
	 * Рассчитать стоимость доставки по параметрам  посылок по России и странам ТС.
	 * 
	 * @param  array  $params
	 * 
	 * @return array
	 */
	public function getServiceCostByParcels(array $params)
	{
		return $this->client->invoke('getServiceCostByParcels2', $params, 'request');
	}

	/**
	 * Рассчитать общую стоимость доставки по международным направлениям
	 * 
	 * @param  array  $params
	 * 
	 * @return array
	 */
	public function getServiceCostInternational(array $params)
	{
		return $this->client->invoke('getServiceCostInternational', $params, 'request');
	}
}
