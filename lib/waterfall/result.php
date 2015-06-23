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
use Rzn\Library\Format\ArrayModification;

class Result implements ArrayAccess
{
    protected $results = [];

    protected $sharedResults = [];

    protected $error = null;

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


    public function getError()
    {
        return $this->error;
    }

    public function setSharedResult($results)
    {
        $this->sharedResults = $results;
    }

    public function addSharedResult($key, $value)
    {
        $this->sharedResults[$key] = $value;
    }

    public function getSharedResults()
    {
        return $this->sharedResults;
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