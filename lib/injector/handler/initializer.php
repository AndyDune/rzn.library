<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 25.06.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Injector\Handler;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;


class Initializer implements ServiceLocatorAwareInterface
{
    protected $serviceManager;
    protected $injector;

    public function __construct($injector, $config = null)
    {
        $this->injector = $injector;
    }

    public function execute($object, $options)
    {
        $this->getServiceLocator()->executeInitialize($object);
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