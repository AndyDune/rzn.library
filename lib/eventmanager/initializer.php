<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 27.11.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\EventManager;
use Rzn\Library\ServiceManager\InitializerInterface;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;

class Initializer implements InitializerInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    public function initialize($instance, $serviceManager)
    {
        if ($instance instanceof EventManagerAwareInterface) {
            $eventManager = $this->serviceManager->get('Rzn\Library\EventManager\EventManager');
            //print_r($eventManager);
            $instance->setConfigurableEventManager($eventManager);
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