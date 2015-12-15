<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 28.08.2015                                      
  * ----------------------------------------------------
  *
  * Проверка параметров водопада.
  */


namespace Rzn\Library\Waterfall;
use Rzn\Library\Config;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;
use ReflectionMethod;

class Check implements ServiceLocatorAwareInterface, ConfigServiceAwareInterface
{

    protected $serviceManager;

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
     * @var \Rzn\Library\Waterfall\WaterfallCollection
     */
    protected $waterfall;


    /**
     * @var \Rzn\Library\Injector\Check
     */
    protected $injectorCheck;

    protected $errors = [];


    public function getErrors()
    {
        return $this->errors;
    }

    public function addError($error)
    {
        if (is_array($error)) {
            $this->errors = array_merge($this->errors, $error);
        } else {
            $this->errors[] = $error;
        }
    }

    /**
     * Быстрая проверка конфига водопада с печатью отчета.
     * Отчет - это массив с метками.
     * Проверяет на существование указанных классов и сервисов.
     * По напечатанному массиву можно определять окончательный набор параметров
     *
     * Если в качестве параметра указана строка, то она определяет имя водопада в основном конфиге.
     * Оттуда и выбирается описание
     *
     * @param string|array|Config $streamDescription
     * @param null|array $config добавочный конфиг к описанному
     */
    public function checkStream($streamDescription, $config = null)
    {
        $this->errors = [];
        $errors = ['drops' => [], 'final' => 'NO', 'error' => 'NO', 'stop' => 'NO', 'route_select' => 'NO'];
        if (is_string($streamDescription)) {
            // Загрузка и сохранение водопада
            if (!isset($this->waterfallConfig['streams'][$streamDescription])) {
                echo 'Водопад не найден: ' . $streamDescription;
                return;
            }
            $streamDescription = $this->waterfallConfig['streams'][$streamDescription];
            if ($config) {
                if ($streamDescription instanceof Config) {
                    $streamDescription = clone($streamDescription);
                } else {
                    $streamDescription = new Config($streamDescription);
                }
                $streamDescription->addConfig($config);
            }

            if ($streamDescription instanceof Config) {
                $streamDescription = $streamDescription->toArray();
            }

        }

        foreach($streamDescription['drops'] as $dropName => $item) {
            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['drops'][$dropName] = $result;
            } else {
                $errors['drops'][$dropName] = 'OK';
            }
        }

        // Проверка конечной функции
        if (isset($streamDescription['final'])) {
            $item = $streamDescription['final'];

            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['final'] = $result;
            } else {
                $errors['final'] = 'OK';
            }

        }
        // Проверка функции ошибки
        if (isset($streamDescription['error'])) {
            $item = $streamDescription['error'];

            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['error'] = $result;
            } else {
                $errors['error'] = 'OK';
            }

        }

        // Функция запускаемая после остановки водопада
        if (isset($streamDescription['stop'])) {
            $item = $streamDescription['stop'];

            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['stop'] = $result;
            } else {
                $errors['stop'] = 'OK';
            }
        }


        // Функция для вычисления маршрута
        if (isset($streamDescription['route_select'])) {
            $item = $streamDescription['route_select'];

            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['route_select'] = $result;
            } else {
                $errors['route_select'] = 'OK';
            }

        }


        return $errors;
    }

    public function checkFunctionDescription($item)
    {
        $errors = [];
        $object = null;
        if (isset($item['invokable'])) {
            if(!class_exists($item['invokable'])) {
                $errors[] = 'Класс НЕ существует: ' . $item['invokable'];
            } else {
                $class = $item['invokable'];
                $object = new $class();
            }
        } else if (isset($item['service'])) {
            $name = $item['service'];
            if (!$this->getServiceLocator()->has($name)) {
                $errors[] = 'Сервис НЕ существует: ' . $name;
            } else {
                $object = $this->getServiceLocator()->get($name);
            }
        } else {
            $errors[] = 'Не указан обязательный ключ в описании';
        }

        if (count($errors)) {
            $this->addError($errors);
            return $errors;
        }

        if ($object) {
            $method = '';
            if (isset($item['method'])) {
                if (!method_exists($object, $item['method'])) {
                    $errors[] = 'Объект поставщик функции для водопада (' . get_class($object) . ') не имеет метода: ' . $item['method'];
                } else {
                    $method = $item['method'];
                }

            } else if (!is_callable($object) and !($object instanceof WaterfallInitializationAwareInterface)) {
                $errors[] = 'Объект поставщик функции для водопада не может быть вызван как функция и не имеет интерфейс WaterfallInitializationAwareInterface';
            } else if (is_callable($object)) {
                $method = '__invoke';
            } else if ($object instanceof WaterfallInitializationAwareInterface) {
                $function = $object->getFunctionForWaterfall();
                if (!is_callable($function)) {
                    $errors[] = 'Метод getFunctionForWaterfall интерфейса WaterfallInitializationAwareInterface должен возвращать анонимную функцию.';
                } else {
                    $reflect = new \ReflectionFunction($function);
                    if ($reflect->getNumberOfParameters() < 2) {
                        $errors[] = 'Метод getFunctionForWaterfall вернул функцию с менее чем 2-мя аргументами.';
                    }

                }
            }

            if ($method) {
                $reflect = new ReflectionMethod($object, $method);
                if ($reflect->getNumberOfParameters() < 2) {
                    $errors[] = 'Метод ' . $method  . ' имеет мало аргументов. Должно быть как минимум 2.';
                }
            }

            $this->addError($errors);
            if (isset($item['injector'])) {
                if (!$errors) {
                    $errors[] = 'OK';
                }

                $this->getInjectorCheck()->inject($object, $item['injector']);
                $errors['injector'] = $this->getInjectorCheck()->getCheckResult();
            }
        }

        return $errors;
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
     * @param \Rzn\Library\Waterfall\WaterfallCollection $waterfall
     */
    public function setWaterfall($waterfall)
    {
        $this->waterfall = $waterfall;
    }

    /**
     * @return \Rzn\Library\Waterfall\WaterfallCollection
     */
    public function getWaterfall()
    {
        return $this->waterfall;
    }

    public function setInjectorCheck($service)
    {
        $this->injectorCheck = $service;
    }

    public function getInjectorCheck()
    {
        return $this->injectorCheck;
    }

}