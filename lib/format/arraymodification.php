<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 *
 *
 * Пример использования:
 *
    $keys = ['subdomain', 'name', 'email', 'phone', 'type'];

    $formData = new Rzn\Library\Format\ArrayModification($_POST);
    $formData->keysLeave($keys);
    $formData->keysAddIfNotExist($keys);
    $formData->addFilterCallback(function($value){
        $value = htmlspecialchars($value);
        $value = substr($value, 0, 50);
        return $value;
    }, ['name', 'type']);
    $formData = $formData->filter()->get();
*/

namespace Rzn\Library\Format;


class ArrayModification
{
    protected $array = [];

    protected $filtersCallback = [];


    public function __construct($array)
    {
        $this->array = $array;
    }

    public function get()
    {
        return $this->array;
    }


    /**
     * Зарегистрировать замыкание как фильтр.
     *
     * @param $function
     * @param null|array|string $keys ключи к которым применяется фильтр
     * @return $this
     */
    public function addFilterCallback($function, $keys = null)
    {
        if ($keys and !is_array($keys)) {
            $keys = [$keys];
        }
        $this->filtersCallback[] = ['function' => $function, 'keys' => $keys];
        return $this;
    }

    /**
     * Запустить процесс фильтрации для массива.
     *
     * @return $this
     */
    public function filter()
    {
        foreach($this->filtersCallback as $filter) {
            if ($filter['keys']) {
                foreach($filter['keys'] as $key) {
                    if (isset($this->array[$key])) {
                        $this->array[$key] = $filter['function']($this->array[$key]);
                    }
                }
            } else {
                foreach ($this->array as $key => $value) {
                    $this->array[$key] = $filter['function']($value);
                }
            }
        }
        return $this;
    }

    /**
     * Оставить в массиве значения только с указанными ключами.
     *
     * @param array $keys массив ключей, которые надо оставить в массиве
     * @return array
     */
    public function keysLeave($keys)
    {
        $array = $this->array;
        if (!is_array($keys))
            $keys = array($keys);
        $result = array();
        foreach($array as $key => $value) {
            if (in_array($key, $keys)) {
                $result[$key] = $value;
            }
        }
        $this->array = $result;
        return $this;
    }

    /**
     * Заполнить значение элемента с ключем $target если он пустой значением с ключем $another, если последнее существует.
     *
     * @param $target
     * @param $another
     * @param string $default
     * @return $this
     */
    function fillFromAnotherIfEmpty($target, $another, $default = '')
    {
        if (!isset($this->array[$target]) or !$this->array[$target]) {
            if (isset($this->array[$another])) {
                $this->array[$target] = $this->array[$another];
            }
        }
        return $this;
    }

    function keysAddIfNotExist($keys, $default = null)
    {
        $array = $this->array;
        if (!is_array($keys))
            $keys = array($keys);
        foreach($keys as $key) {
            if (!array_key_exists($key, $array)) {
                $array[$key] = $default;
            }
        }
        $this->array = $array;
        return $this;
    }

    function keysIntVal($keys)
    {
        $array = $this->array;
        if (!is_array($keys))
            $keys = array($keys);
        $result = array();
        foreach($array as $key => $value) {
            if (in_array($key, $keys)) {
                $result[$key] = intval($value);
            } else {
                $result[$key] = $value;
            }
        }
        $this->array = $result;
        return $this;
    }

    function keysTrim($keys)
    {
        $array = $this->array;
        if (!is_array($keys))
            $keys = array($keys);
        $result = array();
        foreach($array as $key => $value) {
            if (in_array($key, $keys)) {
                $result[$key] = trim($value);
            } else {
                $result[$key] = $value;
            }
        }
        $this->array = $result;
        return $this;
    }


    function keysDoubleVal($keys)
    {
        $array = $this->array;
        if (!is_array($keys))
            $keys = array($keys);
        $result = array();
        foreach($array as $key => $value) {
            if (in_array($key, $keys)) {
                $result[$key] = doubleval($value);
            } else {
                $result[$key] = $value;
            }
        }
        $this->array = $result;
        return $this;
    }

} 