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
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;

class Injector implements ServiceLocatorAwareInterface, ConfigServiceAwareInterface
{
    /**
     * @var \Rzn\Library\Config
     */
    protected $configService;

    protected $serviceManager;

    protected $handlerObject = [];

    /**
     * Инндикатор необходимости инициилизации из конфига.
     * @var bool
     */
    protected $needInit = true;

    protected $defaultOption = [
        'initialize' => ['type' => 'initializer']
    ];

    public function inject($object, $options = null)
    {
        if ($this->needInit) {
            $this->initServicesFromConfig();
            $this->needInit = false;
        }
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
            if (!isset($this->handlerObject[$handler])) {
                throw new Exception('Обработчик инъекции не зарегистрирован: ' . $handler);
            }

            if (!isset($optionValue['options'])) {
                $optionValue['options'] = null;
            }
            $this->handlerObject[$handler]->execute($object, $optionValue['options']);
        }
    }

    public function initServicesFromConfig()
    {
        $configService = $this->getConfigService();
        $config = $configService['injector'];
        if (!$config or !isset($config['handlers'])) {
            return null;
        }
        foreach($config['handlers'] as $name => $params) {
            $class = $params['invokable'];
            if (isset($params['config'])) {
                $arg =  $configService->getNested($params['config']);
            } else {
                $arg = null;
            }
            $this->handlerObject[$name] = new $class($this, $arg);
            $this->getServiceLocator()->executeInitialize($this->handlerObject[$name]);
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

    /**
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->configService;
    }

    public function setConfigService($config)
    {
        $this->configService = $config;
    }

}