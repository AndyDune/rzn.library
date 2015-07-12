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
    protected $name;

    protected $functions = [];
    protected $finalFunction = null;
    protected $errorFunction = null;

    protected $resultShared = false;

    public function __construct($name = null)
    {
        $this->name = $name;
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

    public function addFunction($function)
    {
        $this->functions[] = $function;
        return $this;
    }

    public function setFinalFunction($function)
    {
        $this->finalFunction = $function;
    }

    public function getFinalFunction()
    {
        return $this->finalFunction;
    }

    public function setErrorFunction($function)
    {
        $this->errorFunction = $function;
    }

    public function getErrorFunction()
    {
        return $this->errorFunction;
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
            //pr($this->functions);
            foreach ($this->functions as $function) {
                if (!$this->resultShared) {
                    $resultObject->reset();
                }
                $function($params, $resultObject);
                $params = $resultObject->getResults();
                if ($err = $resultObject->getError()) {
                    break;
                }
            }
            if ($err) {
                /** @var callable $func */
                if ($func = $this->getErrorFunction()) {
                    if (!$this->resultShared) {
                        $resultObject->reset();
                    }
                    $func($params, $resultObject);
                    return $resultObject;
                }
            }
            if ($func = $this->getFinalFunction()) {
                if (!$this->resultShared) {
                    $resultObject->reset();
                }
                $func($params, $resultObject);
                return $resultObject;
            }
            return $resultObject;
        } catch(Exception $e) {
            // todo добавить действия на ошибки самого водопада
        }
    }
}