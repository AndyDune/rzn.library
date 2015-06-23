<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 15.04.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\ServiceManager\Initializer;

namespace Rzn\Library\ServiceManager\Initializer;


use Rzn\Library\ServiceManager\InitializerInterface;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\EventManagerAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;

class EventManager implements InitializerInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;


    /**
     * Метод запускается для каждого вновь создаваемого сервиса.
     *
     * @param $instance
     * @param $serviceManager
     */
    public function initialize($instance, $serviceManager)
    {
        /**
         * Если класс объекта реализует интерфейс ConfigServiceAwareInterface делаем инъекцию сервисом конфигов
         */
        if ($instance instanceof EventManagerAwareInterface) {
            $instance->setEventManager($this->getServiceLocator()->get('event_manager'));
        }
    }

    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceManager;
    }

}