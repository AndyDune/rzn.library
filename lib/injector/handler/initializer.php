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

    /**
     * Запуск инъекции через иньектор сервисов.
     *
     * @param $object
     * @param $options
     */
    public function execute($object, $options)
    {
        $this->getServiceLocator()->executeInitialize($object);
    }

    /**
     * Заглушка для теста.
     *
     * @param $object
     * @param $options
     * @return string
     */
    public function check($object, $options)
    {
        return 'Проверка инъектора через интерфейс не проводится.';
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