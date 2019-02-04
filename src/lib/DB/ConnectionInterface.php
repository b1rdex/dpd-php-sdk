<?php
namespace Ipol\DPD\DB;

/**
 * Интерфейс соединения с БД
 */
interface ConnectionInterface
{
    /**
     * Возвращает инстанс PDO
     * 
     * @return \PDO
     */
    public function getPDO();

    /**
     * Возвращает маппер для таблицы
     *
     * @param string $tableName имя маппера/таблицы
     *
     * @return \Ipol\DPD\DB\TableInterface
     */
    public function getTable($tableName);
}
