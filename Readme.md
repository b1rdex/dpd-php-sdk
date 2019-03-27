# Client SDK DPD
Client SDK - SDK для быстрой разработки клиентских решений для сервиса доставки DPD.

### :heavy_exclamation_mark::heavy_exclamation_mark: Это форк https://bitbucket.org/DPDinRussia/dpd.sdk

### Требования

* PHP 5.4.* или выше
* SOAP
* PDO
* PDO-SQLite

### Установка
Установить SDK можно двумя способами

#### Установка из архива
Для установки скачайте [tar.gz](https://bitbucket.org/DPDinRussia/dpd.sdk/get/master.tar.gz) или [zip](https://bitbucket.org/DPDinRussia/dpd.sdk/get/master.zip), распакуйте его и подключите файл `autoload.php` в Вашем проекте. 

```php

require_once 'path/to/dpd.sdk/src/autoload.php';

```

После этого перейдите в папку SDK и запустите комманду ```composer install``` которая загрузит все зависимости проекта

#### Установка через composer
Добавьте в composer.json проекта следующие строки

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://bitbucket.org/DPDinRussia/dpd.sdk";
  }
]
```

после этого выполните добавление SDK в Ваш проект
```bash
composer require ipol/dpd.sdk:dev-master
```

#### После установки
Обязательно, для того чтобы расчет стоимости работал, необходимо произвести импорт начальных данных. 
Пример загрузчика находится в папке `examples/load_locations.php` внутри архива.

### Настройки
Следующие параметры используются в модуле

* ```UPLOAD_DIR``` - задается путь к директории для хранения запрошенных файлов
* ```DB``` - параметры подключения к БД. Модуль использует БД для хранения списка городов обслуживания и списка ПВЗ. По умолчанию модуль использует локальную SQLite БД.
* ```DB.DSN``` - dsn строка подключения к БД
* ```DB.USERNAME``` - имя пользователя для подключения к БД
* ```DB.PASSWORD``` - пароль пользователя для подключения к БД
* ```DB.DRIVER``` - используемый драйвер при подключении к БД. По умолчанию будет вычеслен автоматически из строки DSN
* ```DB.PDO``` - вместо всех параметров, можно сразу передать готово подключение в виде объекта \PDO
* ```KLIENT_NUMBER``` - клиентский номер в системе DPD
* ```KLIENT_KEY``` - секретный ключ для авторизации в системе DPD
* ```KLIENT_CURRENCY``` - валюта аккаунта
* ```KLIENT_NUMBER_KZ```,```KLIENT_KEY_KZ```,```KLIENT_CURRENCY_KZ``` - параметры для подключения казахского аккаунта
* ```KLIENT_NUMBER_BY```,```KLIENT_KEY_BY```,```KLIENT_CURRENCY_BY``` - параметры для подключения белорусского аккаунта
* ```API_DEF_COUNTRY``` - возможные значения RU,KZ,BY. Код аккаунта по умолчанию
* ```IS_TEST``` - вкл./выкл. тестовый режим
* ```WEIGHT``` - вес товара по умолчанию
* ```LENGTH``` - длина товара по умолчанию
* ```WIDTH```  - ширина товара по умолчанию
* ```HEIGHT``` - высота товара по умолчанию
* ```TARIFF_OFF``` - список тарифов которые НЕ будут использованы в расчетах. возможные значения PCL,CSM,ECN,ECU
* ```DEFAULT_TARIFF_CODE``` - тариф по умолчанию, данный тариф будет выбран если стоимость доставки меньше чем указанов `DEFAULT_TARIFF_THRESHOLD`.
* ```DEFAULT_TARIFF_THRESHOLD``` - макс сумма доставки при которой будет использован тариф по умолчанию
* ```DECLARED_VALUE``` - Включать страховку в стоимость доставки
* ```COMMISSION_NPP_CHECK``` - Включать комиссию за инкассацию наложенным платежом в стоимость доставки, массив вида ```[PERSONE_TYPE_ID => bool, ...]```
* ```COMMISSION_NPP_PERCENT``` - Комиссия от стоимости товаров в заказе (в процентах), %. массив вида ```[PERSONE_TYPE_ID => double, ...]```
* ```COMMISSION_NPP_MINSUM``` - Минимальная сумма комиссии, руб. массив вида ```[PERSONE_TYPE_ID => double, ...]```
* ```COMMISSION_NPP_PAYMENT``` - ID платежных системы, которые означают что оплата будет происходить наложенным платежом . массив вида ```[PERSONE_TYPE_ID => [PAYMENT_SYSTEM_ID, ...], ...]```
* ```COMMISSION_NPP_DEFAULT``` - Если платежную систему определить не удалось, считать ли что оплата будет происходить наложенным платежом по умолчанию?. массив вида ```[PERSONE_TYPE_ID => bool, ...]```

Для хранения настроек в модуле реализован класс `\Ipol\DPD\Config\Config`, в конструктор класса можно передать массив 
опций, тем самым переопределив значения по умолчанию. Так же может быть реализован свой класс для хранения настроек, в этом случае он 
должен реализовывать интерфейс `Ipol\DPD\Config\ConfigInterface`.

### Расчет стоимости доставки
Для расчета стоимости доставки вначале необходимо создать объект описывающий отправку.
Для этого в модуле используется класс `Ipol\DPD\Shipment`

```php
$config = new \Ipol\DPD\Config\Config([
    // ... параметры авторизации
]);

$shipment = new \Ipol\DPD\Shipment($config);

// Указываем города отправления и назначения
$shipment->setSender('Россия', 'Москва', 'г. Москва');
$shipment->setReceiver('Россия', 'Тульская область', 'г. Тула');

// указываем отправку терминал - дверь
$shipment->setSelfPickup(true);
$shipment->setSelfDelivery(false);

// список товаров входящих в отправку
$goods = [
    1 => [
        'NAME'     => 'Название товара',
        'QUANTITY' => 1, // кол-во
        'PRICE'    => 1000, // стоимость за единицу
        'VAT_RATE' => 18, // ставка налога, процент или строка Без НДС
        'WEIGHT'   => 1000, // вес, граммы,
        'DIMENSIONS' => [
            'LENGTH' => 100, // длина, мм,
            'WIDTH'  => 200, // ширина, мм,
            'HEIGHT' => 200, // высота, мм,
        ]
    ],
    
    // ...
];

// объявленная ценность
$goodsPrice = 1000;

// устанавливаем товары входящие в отправку
$shipment->setItems($goods, $goodsPrice);

// так же можно указать тип покупателя и платежную систему
// в зависимости от указанных параметров можно реализовать наценку на доставку при использовании наложенного платежа
$personeTypeId = 1; // значения переменный должны быть использованы в настроках секция COMMISSION_NPP_*
$paySystemId   = 1;
$shipment->setPaymentMethod($personeTypeId, $paySystemId);
```

За сам расчет стоимости доставки в модуле отвечает класс ```\Ipol\DPD\Calculator``` на вход он получает отправку для
которой необходимо произвести расчет стоимости. 

```php
// так можно получить калькулятор
$calc = $shipment->calculator();

// вернет информацию об актуальном тарифе для отправки
$tariff = $calc->calculate();

// а так можно расчитать стоимость доставки конкретного тарифа
$tariff = $calc->calculateWithTariff('PCL');
```

В методах ```calculate``` и ```calculateWithTariff``` калькулятора есть необязательный параметр ```$currency```, 
в котором можно передать код валюты, в этом случае стоимость достаки будет сконвертирована в указанную валюту.
Для поддержки конвертации Вам необходимо создать класс конвертер реализующий интерфейс ```\Ipol\DPD\Currency\ConverterInterface```,
а экземпляр этого класса необходимо передать калькулятору

```php
class Converter implements \Ipol\DPD\Currency\ConverterInterface
{
    // реализация
}

$converter = new Converter();
$tariff = $calc
            ->setCurrencyConverter($converter)
            ->calculate('USD');
```

### Отправка заказа
Для создания заказа в системе DPD сначала необходимо создать объект хранящий информацию об этом заказе.
В модуле за это отвечает класс ```\Ipol\DPD\DB\Order\Model```, данный заказ будет сохранен в БД.

```php
$config = new \Ipol\DPD\Config\COnfig([
    // ...
]);

$shipment = new Shipment($config);
// указываем параметры отправления

$order = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('order')->makeModel();
$order->orderId = 'Внешний код заказа';

// данная модель может заполнить часть параметров из переданной отправки
// в противном случае можно все эти параметры заполнить вручную указав значения соответствующих полей
$order->setShipment($order);

// указываем тариф отправки
$order->serviceCode = 'PCL';
// если не использовать объект отправки, так же необходимо указать вариант доставки
// в нашем случае это терминал - дверь
// $order->serviceVariant = [SELF_PICKUP => true, SELF_DELIVERY => false]

// Дата и время забора
$order->pickupDate = '2017-11-29';
$order->pickupTimePeriod = '9-18';

// данные отправителя
$order->senderName = 'Наименование отправителя';
$order->senderFio = 'ФИО отправителя';
$order->senderPhone = 'Телефон отправителя';
$order->senderTerminalCode = 'Код терминала отправления';

// данные получателя
$order->receiverName = 'Наименование получателя';
$order->receiverFio = 'ФИО получателя';
$order->receiverPhone = 'Телефон получателя';
$order->receiverStreet = 'Улица';
$order->receiverStreetabbr = 'ул.';
$order->receiverHouse = 'дом';
$order->receiverComment = 'инструкция для курьера';
```

После того как все параметры заказа заполнены, нужно вызвать метод `dpd`.
Данный метод возвращает объект класса `Ipol\DPD\Order` позволяющий производить операции с заказом на стороне dpd,
в частности создание, отмену, запрос файла наклеек или накладной, проверка статуса и др.

```php
// создаем заказ в системе dpd
$result = $order->dpd()->create();

// $order->dpd()->cancel(); // отменяем
// $order->dpd()->checkStatus(); // проверка статуса заказа
// $order->dpd()->getLabelFile(); // получить файл с наклейкой
// $order->dpd()->getInvoiceFile(); // получить файл с накладной

// получить созданный ранее заказ и отменить его
$orderId = 1; // внешний ID заказа
$order = \Ipol\DPD\DB\Connection::getInstance($config)->getTable('order')->getByOrderId($orderId);
$order->dpd()->cancel();
```

### Работа с местоположениями и терминалами
При установке модуля и загрзуки первоначальных данных список городов и терминалов сохраняются во внутреннюю БД.
В дальнейшем возможно использовать эти данные в своих целях. Для этого в модуле реализован простейший DataMapper к бд.

Получить доступ к таблице можно следующим образом

```php

$orderTable    = \Ipol\DB\Connection::getInstance($config)->getTable('order');
$locationTable = \Ipol\DB\Connection::getInstance($config)->getTable('location');
$terminalTable = \Ipol\DB\Connection::getInstance($config)->getTable('terminal');

```

Во всех случаях будет возвращен объект класс которого реализует интерфейс `\Ipol\DPD\DB\TableInterface`. 
Помимо этого, в классе могут быть реализованы свои вспомогательные методы. Например у класса местоположений есть 
метод `getByAddress($country, $region, $city, $select = '*')` который ищет местоположение по текстовому 
представлению.

```php

// получим терминалы принимающие наложенный платеж
$items = $terminalTable->find([
    'where' => 'NPP_AVAILABLE = "Y"',
])->fetchAll();

```

### Cron
В модуле реализован класс `\Ipol\DPD\Agents` содержащий готовые методы для выполнения периодических заданий.
В частности в данном классе есть метод обновляющий статусы заказов.

Для использования этих методов необходимо создать отдельный скрипт, в котором вызвать нужный метод. А выполнение данного 
скрипта поставить на cron. 

Следующий пример показывает как создать скрипт обновления статусов заказов каждые 10 минут.

```php
// dpd-check-status.php

$config = new \Ipol\DPD\Config\Config([
    // параметры
]);

\Ipol\DPD\Agents::checkOrderStatus($config);
```

```cron
# crontab
*/10 * * * * /path/to/php /path/to/dpd-check-status.php
```

### Кодировка
Модуль работает с данными в кодировке `UTF-8`, если Ваш проект использует другую кодировку, Вам необходимо самостоятельно
конвертировать передаваемые и получаемые данные. 

Для облегчения конвертации в модуле есть вспомогательный метод позволяющий рекурсивно изменить кодировку переданного 
массива

```php
$data = [
    'PARAM1' => 'Параметр 1'
    'PARAM2' => [
        'SUB1' => 'п 2.1',
        'SUB2' => 'п 2.2',
    ],
]

$convertData = \Ipol\DPD\Utils::convertEncoding($data, 'windows-1251', 'UTF-8');
```

### Примеры
В каталоге `examples/` приведены примеры использования модуля

### Документация
Описание классов, методов и их параметров доступно [здесь](http://ipolh.com/dpd/sdk/namespaces/Ipol.DPD.html)
