<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 24.11.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\EventManager;
use Rzn\Library\Config;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;
use Rzn\Library\ServiceManager\InitializerInterface;

use Zend\EventManager\EventManager as ZendEventManager;

use Zend\Stdlib\PriorityQueue;
use Zend\Stdlib\CallbackHandler;

class EventManager extends ZendEventManager implements ServiceLocatorAwareInterface, ConfigServiceAwareInterface
{
    protected $serviceManager;

    /**
     * @var Config
     */
    protected $config;

    protected $eventConfig = [];

    /**
     * @var InitializerInterface
     */
    protected $interfaceInitializer = array();


    /**
     *
     * @param $config
     * @return $this
     */
    public function setEventConfig($config)
    {
        $this->setEventClass('Rzn\Library\EventManager\Event');
        if ($config and $config instanceof Config) {
            $this->eventConfig = $config->toArray();
        }
        return $this;
    }

    /**
     * Этот метод перенружает оригинальный и объявляет обработчики событий перед непосредственным их вызовом.
     *
     * @param string $event
     * @param null $target
     * @param array $argv
     * @param null $callback
     * @return \Zend\EventManager\ResponseCollection
     */
    public function trigger($event, $target = null, $argv = array(), $callback = null)
    {
        // Зарегистрировать обратотчики событий.
        if (is_string($event) and isset($this->eventConfig[$event])) {
            if (isset($this->eventConfig[$event]['invokables']) and is_array($this->eventConfig[$event]['invokables'])) {
                $this->registerEventsInvokables($this->eventConfig[$event]['invokables'], $event);
            }

            if (isset($this->eventConfig[$event]['factories']) and is_array($this->eventConfig[$event]['factories'])) {
                $this->registerEventsFactories($this->eventConfig[$event]['factories'], $event);
            }

            if (isset($this->eventConfig[$event]['services']) and is_array($this->eventConfig[$event]['services'])) {
                $this->registerEventsServices($this->eventConfig[$event]['services'], $event);
            }
            // Уже зарегистрированные обработчики удаляем.
            unset($this->eventConfig[$event]);
        }
        // Отбработать события
        return parent::trigger($event, $target, $argv, $callback);
    }

    public function registerEventsServices($eventListeners, $event)
    {
        foreach($eventListeners as $listener) {
            $priority = 1;
            if (is_array($listener)) {
                if (isset($listener['priority'])) {
                    $priority = $listener['priority'];
                }
                $listener = $listener[0];
            }
            $object = $this->serviceManager->get($listener);
            $this->attach($event, $object, $priority);
        }

    }

    /**
     * Регистрация слушатьеля события фабрикой.
     * Возможна инициилизация
     *
     * @param $eventListeners
     * @param $event
     */
    public function registerEventsFactories($eventListeners, $event)
    {
        foreach($eventListeners as $listener) {
            $priority = 1;
            if (is_array($listener)) {
                if (isset($listener['priority'])) {
                    $priority = $listener['priority'];
                }
                $listener = $listener[0];
            }

            $object = new $listener();
            $object = $object->createEventListener($this->serviceManager);

            if (count($this->interfaceInitializer)) {
                foreach($this->interfaceInitializer as $interfaceInitializer) {
                    $interfaceInitializer->initialize($object, '');
                }
            }

            $this->attach($event, $object, $priority);
        }
    }

    public function registerEventsInvokables($eventListeners, $event)
    {
        foreach($eventListeners as $listener) {
            $priority = 1;
            if (is_array($listener)) {
                if (isset($listener['priority'])) {
                    $priority = $listener['priority'];
                }
                $listener = $listener[0];
            }
            $object = new $listener();
            if ($object instanceof ServiceLocatorAwareInterface) {
                $object->setServiceLocator($this->serviceManager);
            }

            if (count($this->interfaceInitializer)) {
                foreach($this->interfaceInitializer as $interfaceInitializer) {
                    $interfaceInitializer->initialize($object, '');
                }
            }

            $this->attach($event, $object, $priority);
        }

    }

    public function attach($event, $object = null, $priority = 1)
    {
        // If we don't have a priority queue for the event yet, create one
        if (empty($this->events[$event])) {
            $this->events[$event] = new PriorityQueue();
        }

        /**
         * Если не неализован метод __invoke смотрим есть ли нужный интефейс.
         * Упаковываем в замыкание для того, чтобы скормить Зенду.
         */
        if (!is_callable($object)) {
            if ($object instanceof EventListenerInterface) {
                $object = function($e) use ($object) {
                    return $object->trigger($e);
                };
            }
        }
        // Create a callback handler, setting the event and priority in its metadata
        $listener = new CallbackHandler($object, array('event' => $event, 'priority' => $priority));

        // Inject the callback handler into the queue
        $this->events[$event]->insert($listener, $priority);
        return $listener;
    }


    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {
        $this->config = $service;
        $config = $service;
        if (isset($config['configurable_event_manager']['listeners'])) {
            $this->setEventConfig($config['configurable_event_manager']['listeners']);
        }
        if (isset($config['configurable_event_manager']['initializers'])) {

            foreach($config['configurable_event_manager']['initializers'] as $key => $value) {
                $initializer = new $value();
                if ($initializer instanceof ServiceLocatorAwareInterface) {
                    $initializer->setServiceLocator($this->getServiceLocator());
                }
                $this->addInitializer($initializer);
            }
        }
    }

    /**
     *
     * Внедрение инициализатора через интерфейсы.
     * Этот метод ппедпочтительней сипользовать.
     *
     * @param InterfaceInitializer $object
     * @return $this
     */
    public function addInitializer(InitializerInterface $object)
    {
        $this->interfaceInitializer[] = $object;
        return $this;
    }

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->config;
    }


    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceManager;
    }


}