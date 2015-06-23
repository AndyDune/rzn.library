<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 16.06.2015                                      
  * ----------------------------------------------------
  *
  *
 'waterfall' => [
     'stream' => [
   'doSome' => [
      'drops' => [
                   <описание>,
                   <описание>,
                   ...
                  ]
      'error' => <описание>
      'final' => <описание>
     ]
   ]
 ],
  * <описание> =>
    'service' => 'имя сервиса'
    'invokable' => 'имя сервиса'

  */


namespace Rzn\Library\Waterfall;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;

class WaterfallCollection implements ServiceLocatorAwareInterface
{
    protected $serviceManager;
    protected $waterfallsLoaded = [];

    protected $configService;

    protected $waterfallConfig;

    /**
     * @param $name
     * @param array $params
     * @param null $callback
     */
    public function execute($name, $params = [], $callback = null)
    {
        if (!array_key_exists($name, $this->waterfallsLoaded)) {
            $this->waterfallsLoaded[$name] = $this->loadWaterfall($name);
        }
        $result = $this->waterfallsLoaded[$name]->execute($params);
        if ($callback and is_callable($callback)) {
            return $callback($result);
        }
        return $result;
    }

    public function loadWaterfall($name)
    {
        $waterfall = new Waterfall($name);

        if (isset($this->mediatorConfig['stream'][$name])) {
            $streamDescription = $this->mediatorConfig['stream'][$name];
                foreach($streamDescription['drops'] as $item) {
                    $service = $this->getObjectIfShared($item);
                    $waterfall->addFunction($this->_buildFunction($item, $service, 'drop'));
                }
            if (array_key_exists('final', $streamDescription)) {
                $item = $streamDescription['final'];
                $service = $this->getObjectIfShared($item);
                $waterfall->addFunction($this->_buildFunction($item, $service, 'final'));

            }
            if (array_key_exists('error', $streamDescription)) {
                $item = $streamDescription['error'];
                $service = $this->getObjectIfShared($item);
                $waterfall->addFunction($this->_buildFunction($item, $service, 'error'));

            }

        }

        return $waterfall;
    }


    /**
     * @param $item
     * @param $service
     * @param Waterfall $waterfall
     * @param string $type
     */
    protected function _buildFunction($item, $service, $type = 'drop')
    {
        if (isset($item['method'])) {
            // Указан явно метод слушателя, который надо запустить
            return function ($params, $resultObject) use ($item, $service) {
                return call_user_func([$service, $item['method']], $params, $resultObject);
            };
        } else if ($service instanceof WaterfallInitializationAwareInterface) {
            // Класс слушателя имеет нужный интрефейс для выборки слушающей функции
            return $service->getFunctionForWaterfall($type);
        } else if (is_callable($service)) {
            // Класс объекта имеет метод __invoke
            return function ($params, $resultObject) use ($service) {
                return call_user_func($service, $params, $resultObject);
            };

        } else {
            new Exception('Некорректное описание потока', 3);
        }

    }

    /**
     * @param $channelRetriever
     * @return object
     */
    protected function getObjectIfShared($channelRetriever)
    {
        if (isset($channelRetriever['service'])) {
            $name = $channelRetriever['service'];
            $object = $this->getServiceLocator()->get($name);
        } else if (isset($channelRetriever['invokable'])) {
            $name = $channelRetriever['invokable'];
            $object = new $name();
        }
        return $object;
    }

    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {
        if ($service['waterfall']) {
            $this->waterfallConfig = $service['waterfall'];
        }
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