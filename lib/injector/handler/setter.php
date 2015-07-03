<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 25.06.2015                                      
  * ----------------------------------------------------
  *
  *
  * Пример использования:
    $injector->inject($object, [
        'set_service' => [
            'handler' => 'setter',
            'options' => [
                'set' => 'service',
                'service' => 'session',
                'method' => 'setServiceS'
            ]
        ],

        'set_config' => [
            'handler' => 'setter',
            'options' => [
                'set' => 'config',
                'config' => 'modules',
                'method' => 'setSet'
            ]
        ],
        'set_me' => [
            'handler' => 'setter',
            'options' => [
                'set' => 'invokable',
                'class' => 'TestTest',
                'injector' => [
                    'set_me' => [
                        'handler' => 'setter',
                        'options' => [
                            'set' => 'invokable',
                            'class' => 'TestTest'
                        ]
                    ]
                ]
            ]
        ]

    ]);
*/

namespace Rzn\Library\Injector\Handler;


use Rzn\Library\Injector\Exception;

class Setter
{
    /**
     * @var \Rzn\Library\Injector\Injector
     */
    protected $injector;

    protected $setterHandlers = [];

    protected $setterHandlersObjects = [];

    /**
     * @param \Rzn\Library\Injector\Injector $injector
     * @param null $config
     */
    public function __construct($injector, $config = null)
    {
        $this->injector = $injector;
        $this->setterHandlers = $config;
    }

    public function execute($object, $options)
    {
        if (!isset($options['set'])) {
            throw new Exception('Не указана обязательная опция set.');
        }

        $handler = $this->getHandlerObject($options['set']);
        $handler->execute($object, $options);
    }

    protected function getHandlerObject($name)
    {
        if (!isset($this->setterHandlersObjects[$name])) {
            if (!isset($this->setterHandlers[$name])) {
                throw new Exception('Указан невозможный параметр set: ' . $name);
            }
            $class = $this->setterHandlers[$name];
            $this->setterHandlersObjects[$name] = new $class();
            $this->injector->getServiceLocator()->executeInitialize($this->setterHandlersObjects[$name]);
        }
        return $this->setterHandlersObjects[$name];
    }
}