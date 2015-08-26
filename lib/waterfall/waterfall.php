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

use Rzn\Library\Exception;

class Waterfall
{
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

    public function __construct($name = null, WaterfallCollection $collection)
    {
        $this->name = $name;
        $this->collection = $collection;
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
     * @return null|callable
     */
    public function getStopFunction()
    {
        if (!is_callable($this->stopFunction)) {
            $this->stopFunction = $this->collection->getFunctionFromDescription($this->stopFunction, 'stop');
        }

        return $this->stopFunction;
    }

    /**
     * @param null|array $params
     * @return Result
     */
    public function execute($params = null)
    {
        try {
            $err = null;
            /** @var \Rzn\Library\Waterfall\Result $resultObject */
            $resultObject = new Result();
            if ($this->resultShared) {
                $resultObject->setResults($params);
            }
            //pr($this->functions);
            foreach ($this->functions as $functionName => $function) {
                // Сброс содержимого объекта результатов
                if (!$this->resultShared) {
                    $resultObject->reset();
                }
                $resultObject->setCurrentFunction($functionName);
                if (!is_callable($function)) {
                    // Для следующего запуска функция будет уже создана
                    $this->functions[$functionName] = $function = $this->collection->getFunctionFromDescription($function, 'drop');
                }
                $function($params, $resultObject);
                // Выборка содержимого объекта результатов в виде массива для следующих функций в водопаде
                $params = $resultObject->getResults();

                // В случае останова немедленный возврат объекта результата
                if ($resultObject->isStopped() or
                    ($this->stopDropName and $this->stopDropName == $functionName)) {
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
                 * Дроп водопада обозначио ошибку.
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
            // todo добавить действия на ошибки самого водопада
        }
    }
}