<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 18.06.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Waterfall;

use ArrayAccess;
use Rzn\Library\Exception;
use Rzn\Library\Format\ArrayModification;
use Rzn\Library\Config;

class Result implements ArrayAccess
{
    /**
     * Результат работы фуккции водопада.
     * По умолчанию значение этой переменной передается следующей функции и сбрасывается перед передачей объекта следующей.
     *
     * @var array
     */
    protected $results = [];

    /**
     * Явный (специальный) разделяемый результат работы функций водопада.
     * Используется отдельно от основного массива рез-ов.
     *
     * @var array
     */
    protected $sharedResults = [];

    protected $sharedResultsReadOnly = [];

    protected $error = null;

    /**
     * Индикатор остановки работы водопада.
     *
     * @var null|bool
     */
    protected $finish = null;

    /**
     * Имя текущей (или поледней) функции для выполнения.
     * Это может быть имя дропа, final или error
     *
     * @var string
     */
    protected $currentFunctionName = '';

    /**
     * Флаг остановки водопада.
     * Необходим для тестовых целей. При остановке не происходит запуска следующих дропов, функции ошибки и результата.
     *
     * @var null|array
     */
    protected $stop = null;

    /**
     * @var Waterfall
     */
    protected $waterfall = null;

    public function __construct($waterfall = null)
    {
        $this->waterfall = $waterfall;
    }

    /**
     *
     *
     * @param null $nestString
     * @return array|null|\Rzn\Library\Config
     */
    public function getConfig($nestString = null)
    {
        // При отдельном использовании объекта результатов - нет привязанного водопада
        if (!$this->waterfall) {
            if ($nestString) {
                return null;
            }
            return new Config([]);
        }
        if ($nestString) {
            return $this->waterfall->getConfig()->getNested($nestString);
        }
        return $this->waterfall->getConfig();
    }

    public function setError($error)
    {
        if ($error instanceof Error) {
            $this->error = $error;
            return $this;
        }
        $this->error = new Error();
        if (is_string($error)) {
            $this->error->setMessage($error);
            return $this;
        }
        if (is_integer($error)) {
            $this->error->setCode($error);
            return $this;
        }
        if (is_array($error)) {

            $container = new ArrayModification($error);
            $container->keysLeave(['code',  'CODE', 'c']);
            $container->keysRename(['c' => 'code', 'CODE' => 'code', 'code' => 'code']);
            $container->keysAddIfNotExist(['code']);

            $keyData = $container->get();
            $this->error->setCode($keyData['code']);


            $container = new ArrayModification($error);
            $container->keysLeave(['message',  'mess', 'm', 'MESS']);
            $container->keysRename(['m' => 'message',
                'mess' => 'message',
                'mes' => 'message',
                'MESS' => 'message']);
            $container->keysAddIfNotExist(['message']);

            $keyData = $container->get();

            $this->error->setMessage($keyData['message']);

            return $this;
        }
    }

    /**
     * Синоним метода setError
     * @param $error
     */
    public function error($error)
    {
        $this->setError($error);
    }


    /**
     * Жесткая остановка работы водопада.
     * Подобное применяется в целях теста.
     * Для нормального окончания работы водопада приемнять метод finish.
     *
     * @param $message
     */
    public function stop($message)
    {
        $this->stop = [
            'function' => $this->currentFunctionName,
            'message' => $message
        ];
    }

    /**
     * Произошла ли остановка водопада.
     * Возвращается null при отсутствии остановки или массив формата:
     * [
     *   'function' => 'имя функции на которой произошла остановка'
     *   'message'  => 'сообщение при остановке'
     * ]
     *
     * @return array|null
     */
    public function isStopped()
    {
        return $this->stop;
    }

    /**
     * Фиксация заявки на окончание работы водопада.
     * Если остались еще дропа на очереди - они пропускаются.
     * Сразу запускается финальная функция если она есть.
     *
     * @param $finalParams дополнительные пераметры для передачи финальной функции
     */
    public function finish($finalParams = [])
    {
        if (count($finalParams)) {
            if (!is_array($this->results)) {
                $this->results = [];
            }
            $this->results = array_merge($this->results, $finalParams);
        }
        $this->finish = true;
    }

    /**
     * Проверка на окончание работы водопада.
     *
     * @return bool|null
     */
    public function isFinished()
    {
        return $this->finish;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Внедрение разделяемых результатов.
     *
     * @param $results
     */
    public function setSharedResults($results)
    {
        if ($this->sharedResultsReadOnly) {
            throw new Exception('Есть поля только для чтения - нельзя заменить все.');
        }

        $this->sharedResults = $results;
        return $this;
    }

    /**
     * Добавление разделяемых результатов.
     *
     * @param $key
     * @param $value
     * @param $readOnly установить для выключения повторной записи разделяемого свойства
     * @return $this
     */
    public function addSharedResult($key, $value, $readOnly = false)
    {
        if (isset($this->sharedResultsReadOnly[$key])) {
            throw new Exception('Разделяемый параметр только для чтения');
        }
        if ($readOnly) {
            $this->sharedResultsReadOnly[$key] = true;
        }
        $this->sharedResults[$key] = $value;
        return $this;
    }

    public function getSharedResults()
    {
        return $this->sharedResults;
    }

    public function getSharedResult($key)
    {
        if (isset($this->sharedResults[$key])) {
            return $this->sharedResults[$key];
        }
        return null;
    }

    public function setResults($results)
    {
        $this->results = $results;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function reset()
    {
        $this->results = [];
    }

    /**
     * Указание имени текущей функции для выполнения.
     * Ключ (имя) дропа, final или error
     *
     * @param $functionName
     * @return $this
     */
    public function setCurrentFunction($functionName)
    {
        $this->currentFunctionName = $functionName;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCurrentFunction()
    {
        return $this->currentFunctionName;
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        if (isset($this->results[$offset])) {
            return true;
        }
        return false;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (isset($this->results[$offset])) {
            return $this->results[$offset];
        }
        return null;

    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->results[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->results[$offset]);
    }

}