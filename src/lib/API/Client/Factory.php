<?php

namespace Ipol\DPD\API\Client;

use Ipol\DPD\API\User\UserInterface;

/**
 * Фабрика по созданию клиента к API
 *
 * По умолчанию создает SOAP-клиента
 */
class Factory
{
    /**
     * Возвращает SOAP-клиент для работы с API
     *
     * @param string                           $wsdl
     * @param \Ipol\DPD\API\User\UserInterface $user
     *
     * @return \Ipol\DPD\API\Client\ClientInterface
     */
    public static function create($wsdl, UserInterface $user)
    {
        return new Soap($wsdl, $user);
    }
}
