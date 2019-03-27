<?php
namespace Ipol\DPD\API\Client;

/**
 * Интерфейс подключения к API
 */
interface ClientInterface
{
	/**
     * Выполняет запрос к внешнему API
     *
     * @param  string $method выполняемый метод API
     * @param  array  $args   параметры для передачи
     * @param  string $wrap   название эл-та обертки
     * @return mixed
     *
     * @throws \SoapFault
	 */
	public function invoke($method, array $args = array(), $wrap = 'request', $keys = false);

    /**
     * Устанавливает время жизни кэша
     *
     * @param int $cacheTime
     *
     * @return self
     */
    public function setCacheTime($cacheTime);
}
