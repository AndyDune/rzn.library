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

        $serviceLocator = $this->getServiceLocator();
        if ($serviceLocator->has($params['service'])) {
            $service = $serviceLocator->get($params['service']);
        } else {
            $service = null;
        }

        // В любом случае фиксируем факт передачи параметра
        call_user_func([$object, $method], $service);
    }

    /**
     * Проверка
     *
     * @param $object
     * @param $params
     * @return array
     */
    public function check($object, $params)
    {
        $errors = [];
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'set' . ucfirst($params['set']);
        }

        if (!method_exists($object, $method)) {
            $errors[] = 'Субъект инъекции (' . get_class($object) . ') не имеет целевого метода: ' . $method;
        }

        if (empty($params['service'])) {
            $errors[] = 'Не указан обязательный параметр: service';
        }


        if (!$this->getServiceLocator()->has($params['service'])) {
            $errors[] = 'Такого сервиса не существует: ' . $params['service'];
        }

        return $errors;
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