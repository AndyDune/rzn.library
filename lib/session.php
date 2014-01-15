<?php
/**
 * Dune Framework
 * 
 * Класс - оболочка для работы с сессией.
 * 
 * ----------------------------------------------------
 * | Библиотека: Dune                                  |
 * | Файл: Session.php                                 |
 * | В библиотеке: Dune/Session.php                    |
 * | Автор: Андрей Рыжов (Dune) <dune@rznw.ru>         |
 * | Версия: 1.11                                      |
 * | Сайт: www.rznw.ru                                 |
 * ----------------------------------------------------
 * 
 * 
 * История версий:
 * -----------------
 * 
 * 1.11 (2009 март 26)
 * Метов возврата имени зоны сессии.
 * 
 * 1.10 (2009 февраль 18)
 * Внедрен метод установки идентификатора сессии.
 * 
 * 1.09 (2008 декабрь 09)
 * Новый метод clearZone(). Присвоение области сессии пустого массива. Аналог killZone().
 * 
 * 1.08 (2008 декабрь 05)
 * Добавлен метод getInt() - возврат числа типа integer. Удаляет пробелы.
 * Добавлена реализация интерфейса Countable. Колличество элементов в зоне.
 * 
 * 1.07 (2008 ноябрь 05)
 * Добавлены магич. методы __isset и __unset для массива зоны.
 * 
 * 1.06 (2008 октябрь 21)
 * Добавлен метод getFloat() - возврат числа типа float. Удаляет пробелы, заменяет запятые на точки.
 * 
 * 1.05 (9 октября 2008)
 * Блокировка зоны с ключём.
 * 
 * 1.04 (8 октября 2008)
 * Установка зоны во время вызова синглетона.
 * Изменена проверка на авторизованную сессию.
 * Блокирования зон на всём протяжении сессии.
 * 
 * 1.02 -> 1.03 (2 сетнября 2008)
 * Незначительная ошибка вывода массива области на тестовую печать.
 * Метод setZone() - новый.
 * 
 * 1.01 -> 1.02 (1 сетнября 2008)
 * Введега начальная инициилизация массивов облстей видимости.
 * Метод getZone().
 * 
 * 1.00 -> 1.01 (29 августа 2008)
 * Добавлен метод _toString() - тестовая печать массива текущей области.
 * 
 * 0.97 -> 1.00 (28 августа 2008)
 * Добавлена реализация интерфейса Iterator
 * 
 * 0.96 -> 0.97
 * Добавлена константа ZONE_CAPTCHA
 * 
 * 0.95 -> 0.96
 * Введение статичной переменной $auth - индикатор авторизованной сессии
 * 
 *
 */

namespace Rzn\Library;
class Session implements \Iterator, \ArrayAccess, \Countable
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
    protected $_lokingZoneName = '_lkLKkjasd12_';
    
    static public $auth = false;
    
   /**
    * Иниц. при первом вызове стат. метода и возвращается при последующих
    *
    * @var Session
    * @access private
    */
    static private $instance = NULL;
 
    
    const ZONE_DEFAULT    = 'default____';
    const ZONE_PREFIX     = 'kasjdqzXm';
    
  
/////////////////////////////////////////////////////////////////////
//////////////////////////////      Статичные методы    
  /**
   * Создаёт реализацию класса при первом вызове
   * Возвращает сохранённый указатель объекта при последующих вызовах
   *
   * Вызывает указаталь на объект с системными параметрами
   * 
   * @param string $zone имя зоны. Если не указано - зона по умолчанию
   * @return Session
   */
    static public function getInstance($zone = null)
    {
        if (self::$instance == NULL)
        {
            self::$instance = new Session();
        }
        if ($zone != null)
            self::$instance->openZone($zone);
        else 
            self::$instance->openZone(Session::ZONE_DEFAULT);
        return self::$instance;
    }

    /**
     * Проверка старта сессии
     * true - сессия открыта
     *
     * @return boolean
     */
    static public function started()
    {
        if (self::$instance == null)
        {
            $bool = false;
        }
        else 
        {
            $bool = true;
        }
        return $bool;
    }
    

/////////////////////////////////////////////////////////////////////
//////////////////////////////      Публичные методы
    
    
    /**
     * Открывает зону в пространстве сессии
     *
     * @param string $zone
     */
    public function openZone($zone)
    {
        $this->_zoneName = $zone;
        $this->_usingZone = Session::ZONE_PREFIX . $zone;
        if (!isset($_SESSION[$this->_usingZone]))
        {
            $_SESSION[$this->_usingZone] = array();
        }
        return $this;
    }

    /**
     * Закрывает зону в пространстве сессии, открывая зону по умолчанию.
     * 
     */
    public function closeZone()
    {
        $this->openZone(self::ZONE_DEFAULT);
        return $this;
    }
    
    /**
     * Блокирование текущей зоны от записи или удаления.
     * Действует ра всём протяжении сесии.
     * 
     * @param string $key запирающий ключ
     */
    public function lockZone($key = false)
    {
        $_SESSION[$this->_lokingZoneName][$this->_usingZone] = $key;
    }

    /**
     * Разблокирует зону для записи или удаления.
     * Действует ра всём протяжении сесии.
     * 
     *  @param string $key запирающий ключ. Если не совпал - разблокировка не происходит
     */
    public function unlockZone($key = false)
    {
        if (
              isset($_SESSION[$this->_lokingZoneName][$this->_usingZone]) 
              and ($_SESSION[$this->_lokingZoneName][$this->_usingZone] == $key)
           )
        unset($_SESSION[$this->_lokingZoneName][$this->_usingZone]);
    }

    
    /**
     * Уничтожение всех переменных зоны.
     * 
     */
    public function killZone()
    {
        $this->_canEditZone();
        unset($_SESSION[$this->_usingZone]);
    }
    
    /**
     * Присваивает значение = array()
     */
    public function clearZone()
    {
        $this->_canEditZone();
        $_SESSION[$this->_usingZone] = array();
    }

    
    /**
     * Уничтожение сессии
     */
    public function destroy()
    {
        self::$auth = false;        
        unset($_SESSION);
        session_destroy();
    }

    /**
     * Окончание текущей сесии - сохранение данных
     */
    public function end()
    {
        session_commit();
    }
    
    /**
     * Возвращает идентификатор сессии
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Возвращает весь массив области.
     */
    public function getZone($array_container = false)
    {
        if ($array_container)
            return new \Rzn\Library\ArrayContainer($_SESSION[$this->_usingZone]);
        return $_SESSION[$this->_usingZone];
    }

    /**
     * Установка всей зоны
     */
    public function setZone($array = array())
    {
        $this->_canEditZone();
        $_SESSION[$this->_usingZone] = $array;
    }

    /**
     * Имя текущей зоны.
     */
    public function getZoneName()
    {
        return $this->_zoneName;
    }
    
    /**
     * Фильтрует значение в массиве текущей зоны $_SESSION и возвращат его.
     * Фильтр - дробное число. ! Запятая конвертируется в точку.
     * При отсутствии ключа в массиве - значение по умолчанию $default
     *
     * @param string $name
     * @param integer $default
     * @return float
     */
    public function getFloat($name, $default = 0)
    {
    	if (isset($_SESSION[$this->_usingZone][$name]))
    	{
    		return (float)str_replace(array(',', ' '), array('.', ''), $_SESSION[$this->_usingZone][$name]);
    	}
    	else 
    	{
    		return $default;
    	}
    }
    /**
     * Фильтрует значение в массиве текущей зоны $_SESSION и возвращат его.
     * Фильтр - целое число. ! Пробелы удаляются
     * При отсутствии ключа в массиве - значение по умолчанию $default
     *
     * @param string $name
     * @param integer $default
     * @return integer
     */
    public function getInt($name, $default = 0)
    {
    	if (isset($_SESSION[$this->_usingZone][$name]))
    	{
    		return (int)str_replace(array(' '), array(''), $_SESSION[$this->_usingZone][$name]);
    	}
    	else 
    	{
    		return $default;
    	}
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
        if (empty($_SESSION[$this->_lokingZoneName]))
            return true;
        if (array_key_exists($this->_usingZone, $_SESSION[$this->_lokingZoneName]))
        {
            throw new \Exception('Попытка редактирования заблокированной зоны. Зона: ' . $this->_usingZone);
        }
    }
   /**
    * @access private
    */
    private function __construct($name = '')
    {

    }
////////// Конец описания приватных методов
///////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
///////////////////////////////     Магические методы
    public function __set($name, $value)
    {
        $this->_canEditZone();
        $_SESSION[$this->_usingZone][$name] = $value;
        
        //$_SESSION[$this->_usingZone . $name] = $value;
    }
    public function __get($name)
    {
        if (isset($_SESSION[$this->_usingZone][$name]))
            return $_SESSION[$this->_usingZone][$name];
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
        return isset($_SESSION[$this->_usingZone][$name]);
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
        unset($_SESSION[$this->_usingZone][$name]);
    }
    
    
    public function __toString()
    {
    	$string = '<pre>';
    	ob_start();
    	print_r($_SESSION[$this->_usingZone]);
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
      return reset($_SESSION[$this->_usingZone]);
  }
  // возвращает текущий элемент
  public function current()
  {
      return current($_SESSION[$this->_usingZone]);
  }
  // возвращает ключ текущего элемента
  public function key()
  {
    return key($_SESSION[$this->_usingZone]);
  }
  
  // переходит к следующему элементу
  public function next()
  {
    return next($_SESSION[$this->_usingZone]);
  }
  // проверяет, существует ли текущий элемент после выполнения мотода rewind или next
  public function valid()
  {
    return isset($_SESSION[$this->_usingZone][key($_SESSION[$this->_usingZone])]);
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
        return isset($_SESSION[$this->_usingZone][$key]);
    }
    public function offsetGet($key)
    {
        if (isset($_SESSION[$this->_usingZone][$key]))
            return $_SESSION[$this->_usingZone][$key];
        else 
            return null;
    }
    
    public function offsetSet($key, $value)
    {
        $this->_canEditZone();
        $_SESSION[$this->_usingZone][$key] = $value;
    }
    public function offsetUnset($key)
    {
        $this->_canEditZone();
        unset($_SESSION[$this->_usingZone][$key]);
    }    


////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса Countable
    public function count()
    {
        return count($_SESSION[$this->_usingZone]);
    }

}