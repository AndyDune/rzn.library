rzn.library
===========
Модуль с полезными классами для CMS 1С-Битрикс

Основу программирования на основе библиотеки закладывает конфигурация. Файл конфигурации может содержать любую информацию: описание ключевых параметров, сервисов, помошников компонентов, каналов медиатора и описания водопадов.


## Дерево конфигурации

В кажом модуле, который указан в конфигурации приложения может быть файл */local/modules/<модуль>/config/module.config.php* с базовым содержимым:

Секции под ключами invoke и factory для сервисов, помогаторов, слушателей событий идентичны

```php
return array(
    'service_manager' => [
        'invokables' => [
            '<имя сервиса>' => [
                'name'=> '<полное имя класса>',
                'injector' => <массив с описанием инъекций>
                'shared'=> <true|false - по умолчанию true>, // вкл/откл сохранения объекта для повторного возврата
            ],
        ],
        'factories' => [
           '<имя сервиса>' => [
                'name'=> '<полное имя класса фабрики>',
                'injector' => <массив с описанием инъекций>
                'shared'=> <true|false - по умолчанию true>, // вкл/откл сохранения объекта для повторного возврата
            ],

        ]
    ],
    'view_helpers' => [
    /*
    Возвращаемый объект-помогатор должен иметь метод __invoke для прямого запуска
    */
        'invokables' => [
            '<имя помогатора>' => [
                'name'=> '<полное имя класса>',
                'injector' => <массив с описанием инъекций>
                'shared'=> <true|false - по умолчанию true>, // вкл/откл сохранения объекта для повторного возврата
            ],
        ],
        'factories' => [
           '<имя помогатора>' => [
                'name'=> '<полное имя класса фабрики>',
                'injector' => <массив с описанием инъекций>
                'shared'=> <true|false - по умолчанию true>, // вкл/откл сохранения объекта для повторного возврата
            ],

        ]

    ],
   'configurable_event_manager' => [
        'listeners' => [
            '<имя события>' => [
                'invokables' => [
                    '<имя слушателя - произвольная строка>' => [
                        'name' => '<класс слушателя с методом __invoke>',
                        // включение инъекции по интерфейсам
                        'injector' => [
                            'inject' => [
                                'handler' => 'initializer',
                                'options' => []
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'mediator' => [<массив описание медиаторов>],
    'waterfall' => [<массив описания водопадов>],
    'bitrix_events' => [
        'main' => [ // модуль события битрикса
            'OnPageStart' => [ // имя события битрикса
                '<имя слушателя - произвольная строка>' => [
                        'name' => '<класс слушателя с методом __invoke>',
                        'injector' => []
                ]            
            ]
        ]
    ]
    


```

Рекомендую к просмотру базовую папку для кастомного кода в битриксе [local](https://github.com/AndyDune/bitrix_local). Здесь папка congif содержит в себе основной конфиг, который определяет настройки для всего приложения. Этот файл загружается самым первым.
#### Содержимое файла application.config.php
```php
return array(
    'modules' => [
       // Список подключаемых модулей при загрузке приложения
    ],
    /*
     * Инструкции для запуска крона
     */
    'cron' => [
        'tasks' => [
            'код события крона' => [
                'event' => 'имя события для запуска',
                'minute' => 5,
                'hour' => 2
            ],
        ],
        // Использовать в локальногй конфигурации для разрешения прямого запуска событий
        // todo на продакшене здесь закоментировать
        'direct' => true
    ]
);
// любые свои настроки
```


Описание для подключения в качестве субмодуля смотреть здесь: http://git-scm.com/book/ru/v1/Инструменты-Git-Подмодули

## Присоединение тестовой части конфига
Есть возможность не изменяя файлы конфига, которые размещены в основной части (local/config/application.config.php) в модулях и шаблонах.
Более того, тестовые участки не попадают в репозиторий, остаются только на машине текущего программиста.

Делаем так:
Создаем файл : local/config/local.config.php - кладется рядом с конфигом приложения.
Этот файл не добавляется в репозиторий. В процессе слияния всех доступных файлом конфигурации этот локальный файл загружается последним и перегружает любые участки конфигурационного массива, которые были загружены ранее.

Пример:

local/config/application.config.php содержит

    'api' => [
         'x' => [
             'url' => 'http://api.rzrw.ru/',
             'login' => 'user',
             'password' => 'qwerty'
         ]
    ]

local/config/local.config.php содержит

     'api' => [
         'x' => [
             'url' => 'http://api.test.rzrw.ru/',
         ]
    ]

На выходе получаем:

     'api' => [
         'x' => [
             'url' => 'http://api.test.rzrw.ru/',
             'login' => 'user',
             'password' => 'qwerty'
         ]
    ]

Точно так же можно перегрузить сервисы, хелперы, медиаторы, водопады (для перегрузки дропов нужно дать им символьные ключи).

## Менеджер сервисов

### Описание сервиса в конфин файле модуля

Корневой ключ конфиг. массива `service_manager` Менеджер сервисов позволяет управлять инъекциями, создавать объекты с помощью фабрик, сохранять состония объектов между вызовами в разнах местах приложения.

Ниже собственный класс, который я планирную зарегистрировать в системе как вызываемый сервис. Класс реализует интерфейс `ServiceLocatorAwareInterface` который позволяет при включении инъекций через интерфейс внедрить в объект менеджер сервисов.
```php
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;

class MyService implements ServiceLocatorAwareInterface
{
    protected $sm;
    
    protected $count = 0;
    
    /**
    * Собственный рабочий метод сервиса
    */
    public function countUse()
    {
        $this->count++;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    /**
    * Извлечение сервиса сессии для использовании внутри объекта класса.
    * @return \Rzn\Library\Session
    */
    protected function getSession()
    {
        $this->getServiceLocator()->get('session');
    }
    
    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->sm;
    }
}
```

Описываем сервис в конфиге модуля.

```php
return array(
    'service_manager' => [
        'invokables' => [
            'MySuperService' => [ // Имя может быть любой строкой
                'name'=> 'MyService',
                'injector' => [
                            'inject' => [
                                'handler' => 'initializer',
                         ],
                'shared'=> true // сохранение создаваемого объекта для последующего возврата при вызове
            ],
        ]
    ]
);
```

Для создания и вызова созданного объекта сервиса нужно получить объект менеджера сервисов, для которого в регистри есть специальной метод.
```php 
use Rzn\Librabry\Registry;
/** @var MyService $myobject */
$myobject = Registry:: getServiceMenager()->get('MySuperService');
$myobject->countUse();
echo $myobject->getCount(); // 1


// Однажды созданный объект сохраняется внутри менеджера и возвращается при следующем запросе.

/** @var MyService $myobject */
$myobject = Registry:: getServiceMenager()->get('MySuperService');
$myobject->countUse();
echo $myobject->getCount(); // 2

```


## События

### Перегрузка событий битрикса

#### Классическое присоединение слушателей к событиям

События битрикса по рекомендации описываются в init.php. Как слушатели выступают функции или статичные методы:

```php
// Статичный метод
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("<имя класса>", "имя метода"));

// Функция
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "имя функции");
```
Более продвинутый метод  - это анонимные функции:

```php
use Bitrix\Main\EventManager;
$eventManager = EventManager::getInstance();

$eventManager->addEventHandlerCompatible("iblock", "OnBeforeIBlockElementAdd", function(&$arFields) {
    // код функции
});

```
Применение анонимных функций более удобный вариант - объявление кода на месте, нет конфликтов имен. Это в случае размещения кода в init.php

Но все это плохо при большой кодовой базе.

#### Слушатели событий битрикса на основе rzn.library

Буду использовать некий модуль rzn.test - он должен быть уставновлен в битриксе и прописан в секции *modules* в конфиге приложения (см. ниже).

Главный принцип - описательность. Используются только объекты классов, которые хранятся в кастомных модулях. Наименования классов по парвилам d7.

Интерфейс классов событий заимствован из *ZendFW 2*


Пример класса, который принимает параметры и модифицирует их.

Рассмотрю событие Sale OnBeforeBasketUpdate - оно запускается в скрипте: *\bitrix\modules\sale\general\basket.php* (строка 1450) 

```php
foreach(GetModuleEvents("sale", "OnBeforeBasketUpdate", true) as $arEvent)
    if (ExecuteModuleEventEx($arEvent, array($ID, &$arFields))===false)
        return false;
```
2 параметра:

- $ID - стандартная передача
- $arFields - по ссылке, может быть мождифицировано

В кастомном механизмусе параметры упаковываются в объект \ArrayAccess для возможности изменения параметров внутри слушателей.

```php
namespace Rzn\Test\EventListener\Sale\OnBeforeBasketUpdate;

class ApplyNewPriceForUser
{

    /**
     * @param $e \Rzn\Library\EventManager\Event
     */
    public function __invoke($e)
    {
       /*
        Извлечение параметров, которые передал битрикс
        Это объект, поэмому значения, которые будут в нем изменены передадутся наружу
       */
       /** @var \ArrayAccess  $params */
        $params = $e->getParams();
        
        $ID = $params[0];
        // имеют числовий ключи
        $arFields = $params[1];

        if (!isset($arFields['PRODUCT_ID']) or !$arFields['PRODUCT_ID']) {
            $id = $params[0];
            $data = \CSaleBasket::GetByID($id);
            if (!$data) {
                return;
            }
            $arFields['PRODUCT_ID'] = $data['PRODUCT_ID'];
        }
        
        // Моежм изменить цену для корзины
        $price = $this->getNewPrice();
        if ($price) {
            $arFields['PRICE'] = $price;
        }
        // Параметр будет изменен
        $params[1] = $arFields;
    }

}
```

Класс создан, расположен в модуле по урлу: */local/modules/rzn.test/lib/sale/onbeforebasketupdate/applynewpriceforuser.php* может автоматически загрузиться битриксом.

Для присоединения в качестве слушателя нужно прописать в конфиге модуля */local/modules/rzn.test/config/module.config.php*

```php
'bitrix_events' => [
        'sale' => [
           'OnBeforeBasketUpdate' => [
                'invokables' => [
                    'ApplyNewPriceForUser' => [
                        'name' => 'Rzn\Test\EventListener\Sale\OnBeforeBasketUpdate\ApplyNewPriceForUser',
                    ]
                ]
            ]
        ]
 ]
```
или 

```php
'bitrix_events' => [
        'sale' => [
           'OnBeforeBasketUpdate' => [
                'invokables' => [
                    'ApplyNewPriceForUser' => [
                        'Rzn\Test\EventListener\Sale\OnBeforeBasketUpdate\ApplyNewPriceForUser',
                    ]
                ]
            ]
        ]
 ]
```

## Инъекция 

Инъекции в основном описываются в конфиге вместе c сущностями, к которым они применяются. Сущности: все сервисы (service_manager, helper_manager, custom_service_managers, event_manager), канала медиатора и водопады.

##### Примеры инъекций
```php
    'custom_service_managers' => [
        'models' => [
            'invokables' => [
                'import_task' => [
                    'name' => 'ImportTask',
                    'shared' => false,
                    'injector' => [
                        // Инъекция сервиса (service_manager)
                        'injectEventManager' => [
                            'handler' => 'setter', // обработчик
                            'options' => [
                                'set' => 'service', // сеттер сервисов
                                'service' => 'event_manager',
                                'method' => 'setEventManager'
                            ]
                        ],
                        // Инъекция собственного сервиса (custom_service_managers)
                        'injectMyService' => [
                            'handler' => 'setter', // обработчик
                            'options' => [
                                'set' => 'custom_service', // сеттер собственных сервисов
                                'manager' => 'my_service_manager',
                                'service' => 'my_serive',
                                'method' => 'setMyService'
                            ]
                        ],
                        // Инъекция конфига, любой указанной части
                        'injectApiConfig' => [
                            'handler' => 'setter',
                            'options' => [
                                'set' => 'config',
                                'config' => 'api.x', // последовательность ключей для конфига
                                'method' => 'setApiConfig'
                            ]
                        ],
                        // Инъекция одного параметра
                        'injectSetAvailability' => [
                            'handler' => 'setter',
                            'options' => [
                                'set' => 'params',
                                'param' => false, // Вставляется как ->setSaveAvailability(false)
                                'method' => 'setSaveAvailability'
                            ]
                        ],
                        // Инъекция многих параметров
                        'injectOneTwo' => [
                            'handler' => 'setter',
                            'options' => [
                                'set' => 'params',
                                'paras' => [1, 2], // Вставляется как ->setOneTwo(1, 2)
                                'method' => 'setOneTwo'
                            ]
                        ],
                        // Инъекция нового объекта указанного класса
                        'injectNewInstance' => [
                            'handler' => 'setter',
                            'options' => [
                                'set' => 'invokable',
                                'class' => 'Rzn\Library\GoodClass', 
                                'method' => 'setGood',
                                'injector' => []
                            ]
                        ],
                        
                    ]
                ],
            ]
        ]
    ]

```
Есть возможность делать инъекции через интерфейс:
```php
'waterfall' => [
  'streams' => [
        'exportOrder' => [
            'drops' => [
                'basket_add' => [
                    'invokable' => 'Rzn\Library\BasketAdd',
                    'injector' => [
                        'injectWithInterface' => [
                            'handler' => 'initializer',
                        ],
                    ]
                ],
            ]
        ],
        ...
    ]
]
```

#### Водопад

##### Тестирование описания водопада
Функции для водопада могут представлять сервисы или объекты указанных класов. В описании обязательно будет инъекции. 

Для быстрой начальной проверки валидности водопада есть специальный сервис waterfall_check. Его метод checkStream возвращает массив с результатом.
```php
/** @var Rzn\Library\Waterfall\Check $waterfallCheck */
$waterfallCheck = $sm->get('waterfall_check');
pr($waterfallCheck->checkStream('loadCatalogImportFileFrom1c'));

```

#### Специальный обработчик множественного свойтсва картинки
Задача: сохранять картинки с формы с контролем максимального их числа, с указанием описания и сортировки:

##### Скрипт сохранения
```php

$filesArrayNormalize = function($files) {
    $result = array();

    foreach($files as $nameOption => $array1) {
        foreach($array1 as $id => $array2) {
            foreach($array2 as $nameField => $value) {
                if (!isset($result[$id])) {
                    $result[$id] = array();
                }
                $result[$id][$nameField][$nameOption] = $value;
            }
        }
    }
    return $result;
};

$filesMorePicture = $filesArrayNormalize($_FILES['image']); // <input type="file" name="image[more_picture][3025]">

$saveMorepicture = new Rzn\Library\BitrixTrial\Iblock\MultiFileProperty($config->getNested('infoblocks.ids.shops'));
$saveMorepicture->setPropertyCode('more_picture')
    ->setElementId($ID)
    ->setMaxImages($maxImagesCount)
    ->setDescriptionArray($_POST['description']['more_picture']) // <input type="text" name="description[more_picture][3025]"  value="Картинка">
    ->setSortArray($_POST['order']['more_picture'])<input type="text" name="order[more_picture][3025]" value="100">
    ->setDeleteArray($_POST['delete_image']['more_picture']) // <input type="hidden" value="0" name="delete_image[more_picture][3025]">
    ->setFilesArray($filesMorePicture, 'VALUE') // Внедрение нормализованого массива с данными из формы
    ->save()
;
```
##### Выборка отсортированных даных
```php
$saveMorepicture = new Rzn\Library\BitrixTrial\Iblock\MultiFileProperty($config->getNested('infoblocks.ids.shops'));
$saveMorepicture->setPropertyCode('more_picture');
$saveMorepicture->setElementId($ID);
$morePictures = $saveMorepicture->extractSorted(); // Массив готовый для участия в выводе картинок
```
