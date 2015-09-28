<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 02.09.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library;
use Iterator;
use ArrayAccess;
use Countable;
use InvokeInterface;

class Storage  implements Iterator, ArrayAccess, Countable
{
    /**
     * Используемая в данный момент зона
     *
     * @var string
     * @access private
     */
    private $_usingZone = 'default___';

    private $_zoneName = '';

    /**
     * Служебная переменная с неповторимым названием.
     *
     * @var string
     * @access private
     */
    protected $_lockingZones = [];

    protected $data = [];


    const ZONE_DEFAULT    = 'default____';


    public function invoke($sm)
    {
        $this->openZone(self::ZONE_DEFAULT);
    }


    /**
     * Открывает зону в пространстве данных
     *
     * @param string $zone
     */
    public function openZone($zone)
    {
        $this->_zoneName = $zone;
        $this->_usingZone = $zone;
        if (!isset($this->data[$this->_usingZone]))
        {
            $this->data[$this->_usingZone] = array();
        }
        return $this;
    }

    /**
     * Закрывает зону в пространстве данных, открывая зону по умолчанию.
     *
     */
    public function closeZone()
    {
        $this->openZone(self::ZONE_DEFAULT);
        return $this;
    }

    /**
     * Блокирование текущей зоны от записи или удаления.
     *
     * @param string $key запирающий ключ
     */
    public function lockZone($key = false)
    {
        $this->_lockingZones[$this->_usingZone] = $key;
    }

    /**
     * Разблокирует зону для записи или удаления.
     *
     *  @param string $key запирающий ключ. Если не совпал - разблокировка не происходит
     */
    public function unlockZone($key = false)
    {
        if (
            isset($this->_lockingZones[$this->_usingZone])
            and ($this->_lockingZones[$this->_usingZone] == $key)
        )
            unset($this->_lockingZones[$this->_usingZone]);
    }


    /**
     * Уничтожение всех переменных зоны.
     *
     */
    public function killZone()
    {
        $this->_canEditZone();
        unset($this->data[$this->_usingZone]);
        return $this;
    }

    /**
     * Присваивает значение = array()
     */
    public function clearZone()
    {
        $this->_canEditZone();
        $this->data[$this->_usingZone] = array();
        return $this;
    }


    /**
     * Возвращает весь массив области.
     */
    public function getZone($array_container = false)
    {
        if ($array_container)
            return new \Rzn\Library\ArrayContainer($this->data[$this->_usingZone]);
        return $this->data[$this->_usingZone];
    }

    /**
     * Псевдоним функции getZone
     */
    public function getArrayCopy($array_container = false)
    {
        return $this->getZone($array_container);
    }

    /**
     * Установка всей зоны
     */
    public function setZone($array = array())
    {
        $this->_canEditZone();
        $this->data[$this->_usingZone] = $array;
    }

    /**
     * Имя текущей зоны.
     */
    public function getZoneName()
    {
        return $this->_zoneName;
    }


/////////////////////////////////////////////////////////////////////
//////////////////////////////      Приватные методы

    /**
     * Проверка на вхождение зоны в список заблокированных.
     * Исключение если заблоктровано.
     *
     * @access private
     * @return boolean
     */
    private function _canEditZone()
    {
        if (!$this->_lockingZones) {
            return true;
        }
        if (array_key_exists($this->_usingZone, $this->_lockingZones))
        {
            throw new Exception('Попытка редактирования заблокированной зоны. Зона: ' . $this->_usingZone);
        }
    }
    /**
     * @access public
     */
    public function __construct()
    {

    }
////////// Конец описания приватных методов
///////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
///////////////////////////////     Магические методы
    public function __set($name, $value)
    {
        $this->_canEditZone();
        $this->data[$this->_usingZone][$name] = $value;
    }
    public function __get($name)
    {
        if (isset($this->data[$this->_usingZone][$name]))
            return $this->data[$this->_usingZone][$name];
        else
            return false;
    }


    /**
     * Поддержка isset() перегружено в PHP 5.1
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->data[$this->_usingZone][$name]);
    }

    /**
     * Поддержка unset() перегружено в PHP 5.1
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        $this->_canEditZone();
        unset($this->data[$this->_usingZone][$name]);
    }


    public function __toString()
    {
        $string = '<pre>';
        ob_start();
        print_r($this->data[$this->_usingZone]);
        $string .= ob_get_clean();
        return  $string . '</pre>';
    }
/////////////////////////////
////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса Iterator
    // устанавливает итеретор на первый элемент
    public function rewind()
    {
        return reset($this->data[$this->_usingZone]);
    }
    // возвращает текущий элемент
    public function current()
    {
        return current($this->data[$this->_usingZone]);
    }
    // возвращает ключ текущего элемента
    public function key()
    {
        return key($this->data[$this->_usingZone]);
    }

    // переходит к следующему элементу
    public function next()
    {
        return next($this->data[$this->_usingZone]);
    }
    // проверяет, существует ли текущий элемент после выполнения мотода rewind или next
    public function valid()
    {
        return isset($this->data[$this->_usingZone][key($this->data[$this->_usingZone])]);
    }
/////////////////////////////
////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса ArrayAccess
    /**
     * @param mixed $key
     * @return mixed
     * @access private
     */
    public function offsetExists($key)
    {
        return isset($this->data[$this->_usingZone][$key]);
    }
    public function offsetGet($key)
    {
        if (isset($this->data[$this->_usingZone][$key]))
            return $this->data[$this->_usingZone][$key];
        else
            return null;
    }

    public function offsetSet($key, $value)
    {
        $this->_canEditZone();
        $this->data[$this->_usingZone][$key] = $value;
    }
    public function offsetUnset($key)
    {
        $this->_canEditZone();
        unset($this->data[$this->_usingZone][$key]);
    }


////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса Countable
    public function count()
    {
        return count($this->data[$this->_usingZone]);
    }

}