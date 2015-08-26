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
use Rzn\Library\ServiceManager\InitializerInterface;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;
use Rzn\Library\Injector\InjectorAwareInterface;

class WaterfallCollection implements ServiceLocatorAwareInterface, ConfigServiceAwareInterface, InjectorAwareInterface
{

    /**
     * @var \Rzn\Library\Injector\Injector
     */
    protected $injector;

    protected $serviceManager;

    /**
     * Кеш уже загруженных водопадов.
     * @var array
     */
    protected $waterfallsLoaded = [];

    /**
     * Объект конфига полностью
     *
     * @var \Rzn\Library\Config
     */
    protected $configService;

    /**
     * Часть объекта конфига под ключем waterfall
     *
     * @var \Rzn\Library\Config
     */
    protected $waterfallConfig;

    /**
     * @param $name
     * @param array $params
     * @param null $callback
     */
    /**
     * @param $name
     * @param array $params
     * @return Result
     */
    public function execute($name, $params = [])
    {
        if (!array_key_exists($name, $this->waterfallsLoaded)) {
            $this->waterfallsLoaded[$name] = $this->loadWaterfall($name);
        }
        return $this->waterfallsLoaded[$name]->execute($params);
    }

    public function loadWaterfall($name)
    {
        $waterfall = new Waterfall($name, $this);
        if (isset($this->waterfallConfig['streams'][$name])) {
            $streamDescription = $this->waterfallConfig['streams'][$name];
                foreach($streamDescription['drops'] as $dropName => $item) {
                    // Для тестов возможно не добавлять дропы в водопад
                    if (isset($item['skip']) and $item['skip']) {
                        continue;
                    }
                    if (isset($item['stop']) and $item['stop']) {
                        $waterfall->setStopDropName($dropName);
                    }
                    // Создание функции для дропа отклыдывается на момент запуска
                    $waterfall->addFunction($item, $dropName);
                }
            if (isset($streamDescription['final'])) {
                $item = $streamDescription['final'];
                // Для тестов возможно не добавлять финальную функцию
                if (!isset($item['skip']) or !$item['skip']) {
                    // Функция будет создана непосредствено перед запуском
                    $waterfall->setFinalFunction($item);
                }
            }
            if (isset($streamDescription['error'])) {
                $item = $streamDescription['error'];
                // Для тестов возможно не добавлять функцию ошибки
                if (!isset($item['skip']) or !$item['skip']) {
                    // Функция будет создана непосредствено перед запуском
                    $waterfall->setErrorFunction($item);
                }
            }
            // Функция запускаемая после остановки водопада
            if (isset($streamDescription['stop'])) {
                $item = $streamDescription['stop'];
                // Функция будет создана непосредствено перед запуском
                $waterfall->setStopFunction($item);
            }

            if (isset($streamDescription['result_shared'])) {
                // Включаем запрет сброса объекта результатов между запусками функций водопада.
                $waterfall->setResultShared($streamDescription['result_shared']);
            }

        } else {
            throw new Exception('Водопада не существует: ' . $name);
        }

        return $waterfall;
    }

    /**
     * Этот метод использует водопад из этой коллекции для отложенного создания функции
     *
     * @param $description массив - описание функции водопада
     * @param null $name
     * @return callable
     */
    public function getFunctionFromDescription($description, $name = null)
    {
        $service = $this->getObjectIfShared($description);
        return $this->_buildFunction($description, $service, $name);

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
        if (isset($channelRetriever['invokable'])) {
            $name = $channelRetriever['invokable'];
            $object = new $name();
            // Обработка нового объеката иньектором
            if (isset($channelRetriever['injector'])) {
                $this->getInjector()->inject($object, $channelRetriever['injector']);
            }
        } else if (isset($channelRetriever['service'])) {
            $name = $channelRetriever['service'];
            $object = $this->getServiceLocator()->get($name);
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

    /**
     * @return \Rzn\Library\Injector\Injector
     */
    public function getInjector()
    {
        return $this->injector;
    }

    /**
     * Внедрение водопала делается инъектором
     * @param $injector
     */
    public function setInjector($injector)
    {
        $this->injector = $injector;
    }

}