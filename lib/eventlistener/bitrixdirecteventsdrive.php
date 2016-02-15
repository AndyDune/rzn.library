<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 15.02.2016                                     |
 * ----------------------------------------------------
 *
 */


namespace Rzn\Library\EventListener;
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Bitrix\Main\EventManager;
use Rzn\Library\Config;

class BitrixDirectEventsDrive implements ConfigServiceAwareInterface
{
    /**
     * @var \Rzn\Library\Config
     */
    protected $configService;

    protected $bitrixEvents = [];

    /**
     * @param $e \Rzn\Library\EventManager\Event
     */
    public function __invoke($e)
    {
        $config = $this->getConfigService();

        if ($config['bitrix_direct_events'] and $config['bitrix_direct_events'] instanceof Config) {
            //$this->bitrixEvents = $config['bitrix_direct_events']->toArray();
            $this->registerBitrixEvents($config['bitrix_direct_events']);
        }
    }

    /**
     * Регистрация в событийной модели битрикса обработчикой адаптера.
     */
    protected function registerBitrixEvents($events)
    {
        $eventManager = EventManager::getInstance();

        foreach ($events as $eventModule => $params) {
            if (!$params) {
                continue;
            }
            foreach ($params as $eventName => $listener) {

                foreach($listener as $listenerType => $listeners) {

                    switch ($listenerType) {
                        case 'invokables':
                            $this->registerEventsInvokables($listeners, $eventManager, $eventModule, $eventName);
                            break;
                    }
                }
            }
        }
    }


    /**
     * @param Config $eventListeners
     * @param EventManager $eventManager
     * @param string $eventModule имя модуля
     * @param string $eventName имя события
     */
    public function registerEventsInvokables($eventListeners, $eventManager, $eventModule, $eventName)
    {
        foreach ($eventListeners as $listener) {
            if (!$listener) {
                continue;
            }

            $priority = 100;
            $retriever = $listener;
            if (isset($listener['priority'])) {
                $priority = $listener['priority'];
            }

            if (isset($listener['name'])) {
                $listener = $listener['name'];
            } else if ($listener['class']) {
                $listener = $listener['class'];
            } else {
                $listener = $listener[0];
            }


            $object = new $listener();
            if ($object instanceof ServiceLocatorAwareInterface) {
                $object->setServiceLocator($this->serviceManager);
            }

            if (count($this->interfaceInitializer)) {
                foreach ($this->interfaceInitializer as $interfaceInitializer) {
                    $interfaceInitializer->initialize($object, '');
                }
            }

            if (isset($retriever['injector'])) {
                $this->getInjector()->inject($object, $retriever['injector']);
            }

            $eventManager->addEventHandlerCompatible($eventModule, $eventName, $object, false, $priority);
        }

    }

    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {
        $this->configService = $service;
    }

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->configService;
    }

    /**
     * @return \Rzn\Library\Injector\Injector
     */
    public function getInjector()
    {
        return $this->injector;
    }

    /**
     * Внедрение водопала делается инъектором
     * @param $injector
     */
    public function setInjector($injector)
    {
        $this->injector = $injector;
    }
}