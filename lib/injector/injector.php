<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 02.07.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Injector;
use Rzn\Library\Exception;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;

class Injector implements ServiceLocatorAwareInterface
{

    protected $serviceManager;

    protected $handlerClasses = [];
    protected $handlerObject = [];

    protected $defaultOption = [
        'initialize' => ['type' => 'initializer']
    ];

    public function inject($object, $options = null)
    {
        // По умолчанию делается инициилизация
        if (!$options) {
            $options = $this->defaultOption;
        }
        foreach($options as $optionName => $optionValue) {
            if (isset($optionValue['type'])) {
                $handler = $optionValue['type'];
            } else if (isset($optionValue['handler'])) {
                $handler = $optionValue['handler'];
            } else {
                throw new Exception('Обработчик инъекции не указан.');
            }
            if (!isset($this->handlerClasses[$handler])) {
                throw new Exception('Обработчик инъекции не зарегистрирован: ' . $handler);
            }

            if (!isset($optionValue['options'])) {
                $optionValue['options'] = null;
            }

            if (!isset($this->handlerObject[$handler])) {
                $class = $this->handlerClasses[$handler];
                $handlerOptions = [];
                $handler = new $class($handlerOptions);
                $this->getServiceLocator()->executeInitialize($handler);
                $this->handlerObject[$handler] = $handler;
            }
            $handler->setOptions($optionValue['options']);
            $handler->execute($object);
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