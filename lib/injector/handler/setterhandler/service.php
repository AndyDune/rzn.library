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


namespace Rzn\Library\Injector\Handler\SetterHandler;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;

class Service implements ServiceLocatorAwareInterface
{
    protected $serviceManager;

    public function execute($object, $params)
    {
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'set' . ucfirst($params['set']);
        }
        call_user_func([$object, $method], $this->getServiceLocator()->get($params['service']));
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