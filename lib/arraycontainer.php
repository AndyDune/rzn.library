<?php
/**
 * Copyright (c) 2013 Andrey Ryzhov.
 * All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     rzn.library
 * @subpackage  Format
 * @author      Andrey Ryzhov <info@rznw.ru>
 * @copyright   2013 Andrey Ryzhov.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://rznw.ru
 *
 *
 * Оболочка для массива.
 * Для автоматической обработки несуществующих ключей.
 * Может оставить в массиве ключи только из указанного списка.
 *
 */

namespace Rzn\Library;
class ArrayContainer implements \ArrayAccess, \Iterator, \Countable
{

    protected $_array = array();
    protected $_arraySourse = array();
    protected $_defaultValue = null;

    /**
     * @param $array целевой масив
     * @param null $defaultValue значение по-умолчанию
     * @param bool $nested допускать обработку вложенных массивов.
     */
    public function __construct($array= null, $defaultValue = null, $nested = false)
    {
        $this->init($array, $defaultValue, $nested);
    }

    public function init($array, $defaultValue = null, $nested = false)
    {
        if ($array and is_array($array) and count($array) > 0) {
            $this->_arraySourse = $array;
            foreach ($array as $key =>$value) {
                if ($nested and is_array($value)) {
                    $this->_array[$key] = new ArrayContainer($value, $defaultValue, $nested);
                } else {
                    $this->_array[$key] = $value;
                }
            }
            $this->_defaultValue = $defaultValue;
        } else {
            $this->_array = array();
            $this->_arraySourse = array();
            $this->_defaultValue = null;
        }
        return $this;
    }
    /**
     * Возвращает колличество элементов массива
     * Реализует интрефейс Countable
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_array);
    }


    /**
     * Оставляет в целевом массиве значения с ключами только из указанного в $keys списка
     *
     * @param $keys массив с ключами, которые надо оставить
     * @return ArrayContainer $this
     */
    public function keepKeys($keys)
    {
        if (!is_array($keys))
            $keys = array($keys);
        $tmp = array();
        foreach($this->_array as $key => $value)
        {
            if(in_array($key, $keys))
                $tmp[$key] = $value;
        }
        $this->_array = $tmp;
        return $this;
    }

    /**
     * Возвращает весь массив, если не указан параметр $key
     * Возвращает содержимое ячейки массива с индексом переданным в $key
     *
     * @param string $key
     * @param boolean $multidimentional
     * @param mixed $default
     * @return mixed
     */
    public function get($key = false, $multidimentional = false, $default = null)
    {
        if ($key !== false)
        {
            if ($multidimentional)
            {
                return $this->_getValueInMultidimentional($key, '.', $default);
            }
            else if (isset($this->_array[$key]))
                return $this->_array[$key];
            else
                return false;
        }
        else
            return $this->_array;
    }

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function set($key, $value = '')
    {
        return $this->_array[$key] = $value;
    }
    /**
     * Устанавливает новое значение по умолчанию
     *
     * @param unknown_type $value
     */
    public function setDefaultValue($value)
    {
        $this->_defaultValue = $value;
    }

    /**
     * Проверка установки ключа в массиве.
     *
     * @param mixed $key ключ для проверки
     * @return boolean true - если установлен, false - нет
     */
    public function check($key)
    {
        return isset($this->_array[$key]);
    }

    /**
     * Возврат массива "как есть"
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_array;
    }

    protected function _getValueInMultidimentional($key, $separator, $default)
    {
        $keys = explode($separator, trim($key));
        $array = $this->_array;
        foreach ($keys as $value)
        {
            if(isset($array[$value]))
            {
                $array = $array[$value];
            }
            else
            {
                return $default;
            }
        }
        return $array;
    }

////////////////////////////////////////////////////////////////
///////////////////////////////     Магические методы
    public function __set($name, $value)
    {
        $this->_array[$name] = $value;
    }
    public function __get($name)
    {
        if (isset($this->_array[$name]))
            return $this->_array[$name];
        else
            return $this->_defaultValue;
    }

    public function __toString()
    {
        $string = '<pre>';
        ob_start();
        print_r($this->_array);
        $string .= ob_get_clean();
        return  '</pre>' . $string;
    }
    ////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса ArrayAccess
    /**
     * @param mixed $key
     * @return mixed
     * @access private
     */
    public function offsetExists($key)
    {
        return isset($this->_array[$key]);
    }
    public function offsetGet($key)
    {
        if (isset($this->_array[$key]))
            return $this->_array[$key];
        else
            return $this->_defaultValue;
    }

    public function offsetSet($key, $value)
    {
        $this->__set($key, $value);
        //$this->_array[$key] = $value;
    }
    public function offsetUnset($key)
    {
        unset($this->_array[$key]);
    }
    ////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса Iterator
    // устанавливает итеретор на первый элемент
    public function rewind()
    {
        return reset($this->_array);
    }
    // возвращает текущий элемент
    public function current()
    {
        return current($this->_array);
    }
    // возвращает ключ текущего элемента
    public function key()
    {
        return key($this->_array);
    }

    // переходит к следующему элементу
    public function next()
    {
        return next($this->_array);
    }
    // проверяет, существует ли текущий элемент после выполнения мотода rewind или next
    public function valid()
    {
        //return isset($this->_array[key($this->_array)]);
        return array_key_exists(key($this->_array), $this->_array);
    }
/////////////////////////////
////////////////////////////////////////////////////////////////

}