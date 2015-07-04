<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.06.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Injector;


use Rzn\Library\ServiceManager\InitializerInterface;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;


class Initializer implements InitializerInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * @var Injector
     */
    protected $serviceInjector = null;


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
        if ($instance instanceof InjectorAwareInterface) {
            $instance->setInjector($this->getInjector());
        }
     }

    protected function getInjector()
    {
        if (!$this->serviceInjector) {
            $this->serviceInjector = $this->serviceManager->get('injector');
        }
        return $this->serviceInjector;
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