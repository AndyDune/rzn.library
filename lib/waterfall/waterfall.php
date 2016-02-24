<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 16.06.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Waterfall;
use Rzn\Library\Config;
use Rzn\Library\Exception;
use Rzn\Library\Format\ArrayMergeTrait;

class Waterfall
{
    use ArrayMergeTrait;
    /**
     * Название описанного в конфиге водопада.
     *
     * @var string
     */
    protected $name;

    /**
     * Список последовательно зарускаемых функция (дропов) водопада.
     * Функция может передавать в объект результата ошибку - тогда следующий дроп не выполняется,
     * а происходит запуск функции ошибки.
     * @var array
     */
    protected $functions = [];

    protected $finalFunction = null;
    protected $errorFunction = null;
    protected $stopFunction = null;

    protected $resultShared = false;

    /**
     * Хранит имя долпа, после выпонения которого нужно остановить водопад.
     *
     * @var null|string
     */
    protected $stopDropName = null;

    /**
     * @var WaterfallCollection
     */
    protected $collection;

    /**
     * Хранится функция, которая используется для вычисления маршрута в текущем запуске водопада.
     * null - нет вычислятора маршрута
     *
     * @var null|callable
     */
    protected $routeSelectFunction = null;

    /**
     * Допустимые маршруты.
     *
     * @var null|array
     */
    protected $routes = null;

    /**
     * @var \Rzn\Library\Config
     */
    protected $config;

    /**
     * Параметры дропа по-умолчанию.
     *
     * @var array
     */
    protected $defaultDropParams = [];

    protected $inputParams = null;

    public function __construct($name = null, WaterfallCollection $collection)
    {
        $this->name = $name;
        $this->collection = $collection;
        // инициилизируем для запуска водопада без коллекциии
        $this->config = new Config([]);
    }

    /**
     * Внедрение для дропа собственных параметров.
     * Свои парамтеры функция дропа получает от предудущего или использует входные.
     * Эта функция определяет параметры по-умолчанию. Эти параметры замещаются настоящими при их наличии.
     *
     * Так же сие полезно для тестирования.
     *
     * @param $name
     * @param $params
     */
    public function setDropParams($name, $params)
    {
        if ($params instanceof Config) {
            $params = $params->toArray();
        }
        $this->defaultDropParams[$name] = $params;
    }

    /**
     * Установка входных параметров по-умолчанию.
     *
     * @param $params
     */
    public function setInputParams($params)
    {
        if ($params instanceof Config) {
            $params = $params->toArray();
        }
        $this->inputParams = $params;
    }


    /**
     * Установка имени дропа послок которого нужно остановить выполненеие водопада.
     *
     * @param $string
     */
    public function setStopDropName($string)
    {
        if (!$this->stopDropName) {
            $this->stopDropName = $string;
        }
    }

    /**
     * Управление передачей результатов работы функций водопада.
     *
     * @param bool $flag
     */
    public function setResultShared($flag = false)
    {
        $this->resultShared = $flag;
    }

    /**
     * Регистрация функции дропа. По сути это замыкание, которое определил WaterfallCollection
     *
     * @param callable $function
     * @param string $name имя или ключ функции водопада из объявления в конфиге
     * @return $this
     */
    public function addFunction($function, $name = null)
    {
        $this->functions[$name] = $function;
        return $this;
    }

    /**
     * Возврат функций водопада.
     * Важен для тестов готовности отдельных шагов.
     *
     * @param null|string $name
     * @return array
     */
    public function getFunction($name)
    {
        if (!isset($this->functions[$name])) {
            throw new Exception('Дропа ' . $name . ' не существует');
        }
        $function = $this->functions[$name];

        if (!is_callable($function)) {
            $function = $this->collection->getObjectIfShared($this->functions[$name]);
            return $function;
        }

        throw new Exception('Дроп  ' . $name . ' уже петерял свое описание');

    }

    public function setFinalFunction($function)
    {
        $this->finalFunction = $function;
    }

    /**
     * Возврат функции, которая запускается при успешном проходе водопада.
     * Может отсутствовать.
     *
     * @return null|callable
     */
    public function getFinalFunction()
    {
        if (!$this->finalFunction) {
            return null;
        }

        if (!is_callable($this->finalFunction)) {
            $this->finalFunction = $this->collection->getFunctionFromDescription($this->finalFunction, 'final');
        }

        return $this->finalFunction;
    }

    /**
     * @param callable $function
     */
    public function setErrorFunction($function)
    {
        $this->errorFunction = $function;
    }

    /**
     * Возврат функции, которая срабатывает при ошибке.
     * Может отсутствовать.
     *
     * @return null|callable
     */
    public function getErrorFunction()
    {
        if (!$this->errorFunction) {
            return null;
        }

        if (!is_callable($this->errorFunction)) {
            $this->errorFunction = $this->collection->getFunctionFromDescription($this->errorFunction, 'error');
        }

        return $this->errorFunction;
    }


    /**
     * @param callable $function
     */
    public function setStopFunction($function)
    {
        $this->stopFunction = $function;
    }

    /**
     * Выбор функции для запуска в конце очереди дропов водопада.
     *
     * @return null|callable
     */
    public function getStopFunction()
    {
        if (!$this->stopFunction) {
            return null;
        }
        if (!is_callable($this->stopFunction)) {
            $this->stopFunction = $this->collection->getFunctionFromDescription($this->stopFunction, 'stop');
        }

        return $this->stopFunction;
    }

    /**
     * Возвращает имя маршрута, который был вычислен в специальной функции
     * Функция указывается в конфигурации водопада под ключем route_select
     *
     * @param $params
     * @param Result $resultObject
     * @return null|string имя маршрута
     */
    public function getRouteNameSelected($params, $resultObject)
    {
        if (!$this->routeSelectFunction) {
            return null;
        }
        /** @var callable $function */
        $function = $this->routeSelectFunction;
        return $function($params, $resultObject);
    }

    /**
     * Внедрение функции для вычисления текущего маршрута.
     *
     * @param callable $function
     */
    public function setRouteSelectFunction($function)
    {
        $this->routeSelectFunction = $function;
    }

    /**
     * Внедрение массива для описания маршрутов водопада.
     *
     * @param $array
     */
    public function setRoutes($array)
    {
        $this->routes = $array;
    }


    /**
     * Внедрение конфига с описание этого водопада.
     * Это тоолько часть конфига сайта.
     *
     * @param \Rzn\Library\Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Возвращает конфиг с описанием текущего водопада.
     * Используется в классе Result для организации доступа из любой функции к конфигу.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     * @param null|array $params
     * @param null|string $route Имя марштрута для запуска
     * @return Result
     */
    public function execute($params = null, $route = null)
    {
        try {
            if ($this->inputParams) {
                $params = $this->arrayMerge($this->inputParams, $params);
            }

            $resultObject = new Result($this);
            if ($this->resultShared) {
                $resultObject->setResults($params);
            }

            // При запуске водопада явно указан маршрут
            if ($route) {
                if (!isset($this->routes[$route])) {
                    throw new Exception('Нет такого маршрута: ' . $route, 100);
                }
                $route = $this->routes[$route];
            } else if ($this->routeSelectFunction) {
                $route = $this->getRouteNameSelected($params, $resultObject);
                // Функция возврата маршрута может маршрут не возвращать, но устанавливать параметры
                $params = $this->arrayMerge($params, $resultObject->getResults());
                if ($route) {
                    // Выборка маршрута из функции, указанной в конфиге
                    if (!isset($this->routes[$route])) {
                        throw new Exception('Нет такого маршрута: ' . $route, 100);
                    }
                    $route = $this->routes[$route];
                }
            }
            // Ошибка может прийти из селектора маршрута, если он задан
            $err = $resultObject->getError();
            if (!$err and !$resultObject->isFinished()) {
                // Если селектор маршрута не выдал ошибок и не остановил работу водопада
                foreach ($this->functions as $functionName => $function) {
                    // Пропуск дропа водапада на указанном маршруте - если маршрут указан
                    if ($route and !in_array($functionName, $route)) {
                        continue;
                    }

                    // Сброс содержимого объекта результатов
                    if (!$this->resultShared) {
                        $resultObject->reset();
                    }

                    // В результат помещаем имя текущей функции - для допустимого дебага
                    $resultObject->setCurrentFunction($functionName);
                    if (!is_callable($function)) {
                        // Для следующего запуска функция будет уже создана
                        $this->functions[$functionName] = $function = $this->collection->getFunctionFromDescription($function, 'drop');
                    }

                    if (isset($this->defaultDropParams[$functionName])) {
                        // Для дропа есть параметры по-умолчанию
                        $params = $this->arrayMerge($this->defaultDropParams[$functionName], $params);
                    }

                    $function($params, $resultObject);
                    // Выборка содержимого объекта результатов в виде массива для следующих функций в водопаде
                    $params = $resultObject->getResults();

                    // В случае останова немедленный возврат объекта результата
                    if ($resultObject->isStopped() or
                        ($this->stopDropName and $this->stopDropName == $functionName)
                    ) {
                        $resultObject->stop('Остановка по инструкции из конфига.');
                        if ($func = $this->getStopFunction()) {
                            if (!$this->resultShared) {
                                $resultObject->reset();
                            }
                            $resultObject->setCurrentFunction('stop');
                            $func($params, $resultObject);
                        }

                        return $resultObject;
                    }

                    /*
                     * Дроп водопада обозначил ошибку.
                     * Прекращается работа водопада и создается причина для запуска функции ошибки.
                     */
                    if ($err = $resultObject->getError()) {
                        break;
                    }

                    /*
                     * Проверка на прекращение водопада
                     * В отличие от остановки происходит запуск финальной функции.
                     */
                    if ($resultObject->isFinished()) {
                        break;
                    }

                }
            }
            if ($err) {
                if ($func = $this->getErrorFunction()) {
                    // Если есть функция ошибки - запускаем ее
                    if (!$this->resultShared) {
                        $resultObject->reset();
                    }
                    $resultObject->setCurrentFunction('error');
                    $func($params, $resultObject);
                    return $resultObject;
                }
            }
            if ($func = $this->getFinalFunction()) {
                // Если есть финальная функция - запускаем ее
                if (!$this->resultShared) {
                    $resultObject->reset();
                }
                $resultObject->setCurrentFunction('final');
                $func($params, $resultObject);
                return $resultObject;
            }
            return $resultObject;
        } catch(Exception $e) {
            // todo возможно усовершенствовать реакцию на ошибки - пока отправка ошибки наружу
            throw new Exception($e->getMessage(), $e->getCode());
            //echo $e->getMessage();
        }
    }
}