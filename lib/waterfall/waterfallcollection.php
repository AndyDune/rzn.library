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
use Rzn\Library\Config;
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
     * Прямой (рабочий) запуск водопада.
     * Повторный запуск использует ранее загруженный водопад.
     *
     * @param $name
     * @param array $params
     * @param string|null $route имя маршрута для запуска
     * @return Result
     */
    public function execute($name, $params = [], $route = null)
    {
        return $this->getWaterfall($name)->execute($params, $route);
    }

    /**
     * Получить объект водопаджа.
     * Без указания второго параметра объект сохраняется для следующего запроса.
     *
     * Примерная перегрузка данных
     *
    $waterfall->getWaterfall('test', [
                            'drops' => [
                                'main' => [
                                    'stop' => 0,
                                    'skip' => 1
                                ],
                                'final' => [
                                    'skip' => 1
                                ],
                                'stop' => [
                                    'skip' => 1
                                ],
                            ]
                        ])->execute();

     *
     * @param $name
     * @param array $params
     * @param array|null $config Прямая инъекция настроек для перегрузки тех, что из основного конфига берутся
     * @return Waterfall
     */
    public function getWaterfall($name, $config = null)
    {
        if ($config and is_array($config)) {
            /*
                Передан массив для перегрузки настроек из конфига
                Не сохраняется для дальнейшего вызова
            */
            if (isset($this->waterfallConfig['streams'][$name])) {
                // Если есть базовое описание водопада - сливаем с явно указанным для запуска
                if ($this->waterfallConfig['streams'][$name] instanceof Config) {
                    // Допутим объект Config
                    $baseConfig = $this->waterfallConfig['streams'][$name]->toArray();
                } else {
                    $baseConfig = $this->waterfallConfig['streams'][$name];
                }
                $configObject = new Config($baseConfig);
                $configObject->addConfig($config);
                $config = $configObject;
            }
            $waterfall = $this->loadWaterfall($name, $config);
        } else if(array_key_exists($name, $this->waterfallsLoaded)) {
            // Водопад был загружен ранее
            $waterfall = $this->waterfallsLoaded[$name];
        } else {
            // Загрузка и сохранение водопада
            if (!isset($this->waterfallConfig['streams'][$name])) {
                throw new Exception('Водопада не существует: ' . $name);
            }
            $this->waterfallsLoaded[$name] =
            $waterfall = $this->loadWaterfall($name, $this->waterfallConfig['streams'][$name]);
        }
        return $waterfall;
    }

    /**
     * Загрузка водопада.
     *
     * @param $name
     * @param array $streamDescription массив с описанием водопада
     * @return Waterfall
     */
    public function loadWaterfall($name, $streamDescription)
    {
        $waterfall = new Waterfall($name, $this);
        $waterfall->setConfig($streamDescription);
        foreach($streamDescription['drops'] as $dropName => $item) {
            // Для тестов возможно не добавлять дропы в водопад
            if (isset($item['skip']) and $item['skip']) {
                continue;
            }
            if (isset($item['stop']) and $item['stop']) {
                $waterfall->setStopDropName($dropName);
            }

            // В конфиге есть параметры для дропа по-умолчанию.
            if (isset($item['params']) and $item['params']) {
                $waterfall->setDropParams($dropName, $item['params']);
            }

            // Создание функции для дропа отклыдывается на момент запуска
            $waterfall->addFunction($item, $dropName);
        }

        if (isset($streamDescription['params'])) {
            $waterfall->setInputParams($streamDescription['params']);
        }

        // Загрузка конечной функции
        if (isset($streamDescription['final'])) {
            $item = $streamDescription['final'];
            // Для тестов возможно не добавлять финальную функцию
            if (!isset($item['skip']) or !$item['skip']) {
                // Функция будет создана непосредствено перед запуском
                $waterfall->setFinalFunction($item);
            }
        }
        // Загрузка функции ошибки
        if (isset($streamDescription['error'])) {
            $item = $streamDescription['error'];
            // Для тестов возможно не добавлять функцию ошибки
            if (!isset($item['skip']) or !$item['skip']) {
                // Функция будет создана непосредствено перед запуском
                $waterfall->setErrorFunction($item);
            }
        }

        // Массив с описанием маршрутов.
        if (isset($streamDescription['routes'])) {
            if ($streamDescription['routes'] instanceof Config) {
                $item = $streamDescription['routes']->toArray();
            } else {
                $item = $streamDescription['routes'];
            }
            $waterfall->setRoutes($item);
        }

        // Функция для вычисления маршрута во время работы водопада.
        if (isset($streamDescription['route_select'])) {
            $item = $this->getFunctionFromDescription($streamDescription['route_select']);
            $waterfall->setRouteSelectFunction($item);
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