rzn.library
===========
Модуль с полезными классами для CMS 1С-Битрникс

Основу программирования на основе библиотеки закладывает конфигурация. Файл конфигурации может содержать любую информацию: описание ключевых параметров, сервисов, помошников компонентов, каналов медиатора и описания водопадов.

Рекомендую к просмотру базовую папку для кустомного кода в битриксе [local](https://github.com/AndyDune/bitrix_local). Здесь папка congif содержит в себе основной конфиг, который определяет настройки для всего приложения. Этот файл загружается самым первым.
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

#### Инъекция 

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
