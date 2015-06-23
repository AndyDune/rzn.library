<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 01.11.14                                      
  * ----------------------------------------------------
  *
  */
return array(
    'service_manager' => array(
        'factories' => array(
            'Rzn\Library\Component\HelperManager' => 'Rzn\Library\Component\HelperManagerFactory',
        ),
        'invokables' => array(
            'session'  => 'Rzn\Library\Session',
            'response' => 'Rzn\Library\Response',
            'request'  => 'Rzn\Library\Request',

            'Rzn\Library\EventManager\EventManager' => 'Rzn\Library\EventManager\EventManager',

            'IncludeComponentWithTemplate' => 'Rzn\Library\Component\IncludeWithTemplate',

            'array_container' => 'Rzn\Library\ArrayContainer',
            'test_data'       => 'Rzn\Library\Directory\AsArray'
            ,'cookie'         => 'Rzn\Library\Cookie'
            ,'completion_tasks' => 'Rzn\Library\CompletionTasks'
            ,'mediator'         => 'Rzn\Library\Mediator\Mediator'
            ,'waterfall'        => 'Rzn\Library\Waterfall\WaterfallCollection'

        ),

        'aliases' => array(
            'helper_manager' => 'Rzn\Library\Component\HelperManager',
            'plugin_manager' => 'Rzn\Library\Component\HelperManager',
            'ComponentIncludeWithTemplate' => 'IncludeComponentWithTemplate',
            'event_manager' => 'Rzn\Library\EventManager\EventManager'
        ),

        'initializers' => [
            'Rzn\Library\ServiceManager\InterfaceInitializer'
            , 'Rzn\Library\EventManager\Initializer'
            , 'Rzn\Library\ServiceManager\Initializer\CookieService'
            , 'Rzn\Library\ServiceManager\Initializer\ConfigService'
            , 'Rzn\Library\ServiceManager\Initializer\EventManager'
            , 'Rzn\Library\ServiceManager\Initializer\CompletionTasks'
            , 'Rzn\Library\Mediator\Initializer'
            , 'Rzn\Library\Waterfall\Initializer'
        ]
    ),
    'view_helpers' => array (
        'invokables' => array(
            'url' => 'Rzn\Library\Component\Helper\Url',
            'isAjax' => 'Rzn\Library\Component\Helper\IsAjax',
            'getFileArray' => 'Rzn\Library\Component\Helper\GetFileArray',
            'printOuterLink' => 'Rzn\Library\Component\Helper\PrintOuterLink',
            'truncateHtml' => 'Rzn\Library\Component\Helper\TruncateHtml',
            'translate' => 'Rzn\Library\Component\Helper\Translate',
            'placeholder' => 'Rzn\Library\Component\Helper\Placeholder',
            'config' => 'Rzn\Library\Component\Helper\Config',
            'isPathBeginWith' => 'Rzn\Library\Component\Helper\IsPathBeginWith'
            , 'addCss'        => 'Rzn\Library\Component\Helper\AddCss'
            , 'addJs'        => 'Rzn\Library\Component\Helper\AddJs'
            , 'phoneNumberFormat' => 'Rzn\Library\Component\Helper\PhoneNumberFormat'
            , 'showWithRename' => 'Rzn\Library\Component\Helper\ShowWithRename'
        ),
        'aliases' => array(
            'viewFilePath' => 'viewFilesPath'
        ),
        'initializers' => array(
            'Rzn\Library\ServiceManager\InterfaceInitializer'
            , 'Rzn\Library\ServiceManager\Initializer\CookieService'
            , 'Rzn\Library\ServiceManager\Initializer\ConfigService'
        )
    ),


    'configurable_event_manager' => [
        'listeners' => [
            'init.post' => array(
                'invokables' => [
                    // Прикрепляет к событиям битрикса конфигурируемые
                    'Rzn\Library\EventListener\BitrixEventsDrive' => 'Rzn\Library\EventListener\BitrixEventsDrive'
                ],
            ),
        ],
        'initializers' => [
            'Rzn\Library\ServiceManager\Initializer\CookieService'
            , 'Rzn\Library\ServiceManager\Initializer\ConfigService'
            , 'Rzn\Library\ServiceManager\InterfaceInitializer'
            , 'Rzn\Library\ServiceManager\Initializer\CompletionTasks'
            , 'Rzn\Library\Mediator\Initializer'
            , 'Rzn\Library\Waterfall\Initializer'
        ]

    ],

    'bitrix_events' => [
        'main' => [
            'OnBeforeProlog' => array(
                // Присоединение конфига из шаблона
                'invokables' => ['Rzn\Library\EventListener\AttachTemplateConfig' =>
                    'Rzn\Library\EventListener\AttachTemplateConfig'],
            )
            , 'OnLocalRedirect' => array(
                'invokables' => [
                    'Rzn\Library\EventListener\ExecuteCompletionTasks' =>
                        'Rzn\Library\EventListener\ExecuteCompletionTasks'
                ]
            )
            , 'OnAfterEpilog' => array(
                'invokables' => [
                    'Rzn\Library\EventListener\ExecuteCompletionTasks' =>
                        'Rzn\Library\EventListener\ExecuteCompletionTasks'
                ]
            )

        ]
    ]


    /*
     * Шаблон для описания слушателей событий.
    'configurable_event_manager' => [
        'listeners' => [
            '<event_name>' => array(
                'invokables' => [],
                'factories'  => [],
                'services'   => []
            )
        ]
    ],
    // Продолжение произвольного события битрикса.
    'bitrix_events' => [
        '<module>' => [
            '<event_name>' => array(
                'invokables' => [],
                'factories'  => [],
                'services'   => []
            )
        ]
    ]

    */


);
