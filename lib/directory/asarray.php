<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 *
 *
 * Использование папки как массива с сохранением данных в файлы.
 * Полезно для быстрого тестирования.
 *
 */

namespace Rzn\Library\Directory;
use Rzn\Exception;

class AsArray implements \Iterator, \ArrayAccess
{
    protected $_data    = array();
    protected $_isWrite = array();
    protected $_isFile = array();

    protected $_path = '';
    protected $_tempFolder = '/tmp';

    protected $_doneBeforePageOut = false;

    static private $instance = array();

    protected $_iteratorCurrentPointer = 0;
    protected $_iteratorTotalPoints = 0;
    protected $_iteratorCurrentKey = null;

    protected $_fileNames    = array();
    protected $_getFileNames = false;

    /**
     * Вызов объекта.
     * Версия объекта определяется парметром $path
     *
     * @param string $path путь к папке с файлами. От корня сайта. Если не сущесвует - прерывание.
     * @return AsArray
     */
    static function getInstance($path = null)
    {
        if (!$path)
            $path = 'tmp';

        if (!array_key_exists($path,self::$instance))
        {
            self::$instance[$path] = new AsArray($path);
        }
        return self::$instance[$path];
    }

    public function saveArray($key, $array)
    {
        ob_start();
        print_r($array);
        $this->_setData($key, ob_get_clean());
        return $this;
    }

    /**
     * Сохранение измененных данных в файле.
     *
     * @return boolean Возвращает false если не удалось создать временную папку.
     */
    public function commit()
    {
        if (!count($this->_isWrite))
            return true;
        $temp = $this->_path . $this->_tempFolder;
        if (!is_dir($temp))
        {
            if (!mkdir($temp))
                throw new Exception('Невозможно создать временную директорию: ' . $temp);
            //return false;
        }

        foreach ($this->_isWrite as $key => $value)
        {
            $file_name_full_info = $this->_path . '/' . $key;
            if (empty($this->_data[$key]) and 0)
            {

                if ($this->_isFile[$key])
                    $this->_unlink($file_name_full_info, 3, 1000);

                continue;
            }
            $data = (string)$this->_data[$key];

            /*
            $data_o = Dune_String_Factory::getStringContainer($data);
            if ($data_o->len() < 1)
            */

            if (strlen($data) < 1)
                continue;
            $file = uniqid('tmp_', true) . '.txt';
            $file_name_full_temp = $temp . '/' . $file;
            file_put_contents($file_name_full_temp, $data);

            if ($this->_unlink($file_name_full_info, 3, 1000))
                rename($file_name_full_temp, $file_name_full_info);
            $this->_unlink($file_name_full_temp, 3, 1000);

            /*            if (is_file($file_name_full_info))
                            unlink($file_name_full_info);
                          unset($this->_isWrite[$key]);
            */
        }
        return true;
    }

    protected function _unlink($file, $try = 1, $time = 0)
    {
        if (!is_file($file))
            return 1;
        for($x = 1; $x <= $try; $x++)
        {
            if (unlink($file))
                return 2;
            if ($time)
            {
                time_nanosleep(0, $time);
            }
        }
        return false;
    }

    /**
     * Конструктр.
     * Открыт для создания тестового сервиса.
     *
     * @param string $path
     */
    public function __construct($path = 'tmp')
    {
        $path = trim($path, '/ ');

        $this->_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
        if (!is_dir($this->_path))
            throw new Exception('Нет нужной папки: ' . $this->_path);
        if (!is_writeable($this->_path))
            throw new Exception('Папка не доступна для записи: ' . $this->_path);

    }

    /**
     * Возврат данных файла или из сохраненой переменной.
     *
     * @param string $name
     * @return mixed Значения: null - нет данных, false - ошибка чтения данных.
     * @access private
     */
    protected function _getData($name)
    {
        $result = $this->_checkData($name);
        if (!is_null($result))
        {
            return $result;
        }
        $this->_getFileContent($name);
        return $this->_checkData($name);
    }

    /**
     * Проверка на повторное чтение файла.
     *
     * Возврат:
     *  - считанные ранее данные из файла;
     *  - false, если файл существует, но данные считать не удалось;
     *  - null - файл не считывался.
     *
     * @param string $name имя файла
     * @return mixed
     */
    protected function _checkData($name)
    {
        $result = null;
        if (isset($this->_data[$name]))
        {
            $result = $this->_data[$name];
        }
        else if (isset($this->_isFile[$name]))
        {
            $result = false;
        }
        return $result;
    }

    /**
     * Устанавливает содержимое файла.
     *
     * @param string $name имя файла
     * @param string $value содержимое файла
     * @access private
     */
    protected function _setData($name, $value)
    {
        $this->_data[$name] = $value;
        $this->_isWrite[$name] = true;
    }


    /**
     * Читает содержимое файла с указанным именем.
     * Устанавливает флаг существования файла.
     *
     * @param unknown_type $name
     * @return string или null при отсутствии файла.
     * @access private
     */
    protected function _getFileContent($name)
    {
        $result = null;
        $file = $this->_path . '/' . $name;
        if (is_file($file))
        {
            $this->_data[$name] = file_get_contents($file);
            $this->_isFile[$name] = true;
            $result = $this->_data[$name];
        }
        else
        {
            $this->_isFile[$name] = false;
        }
        return $result;
    }


    public function __set($key, $value)
    {
        $this->_setData($key, $value);
    }

    public function __get($key)
    {
        return $this->_getData($key);
    }

    /**
     * Читает имена всех файлов.
     *
     * @return boolean всегда true
     * @access private
     */
    protected function _readFileNames()
    {
        if ($this->_getFileNames)
            return true;
        $dir = new \DirectoryIterator($this->_path);
        foreach ($dir as $value)
        {
            if ($value->isFile())
            {
                $this->_fileNames[] = $value->getFilename();
            }
            $this->_iteratorTotalPoints = count($this->_fileNames);
        }
        return $this->_getFileNames = true;
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
        $data = $this->_getData($key);
        if (is_null($data) or $data === false)
        {
            return false;
        }
        return true;
    }
    public function offsetGet($key)
    {
        return $this->_getData($key);
    }

    public function offsetSet($key, $value)
    {
        return $this->_setData($key, $value);
    }
    public function offsetUnset($key)
    {
        unset($this->_data[$key]);
        $this->_isWrite[$key] = true;
    }
    ////////////////////////////////////////////////////////////////
    ///////////////////////////////     Методы интерфейса Iterator

    // устанавливает итеретор на первый элемент
    public function rewind()
    {
        $this->_readFileNames();
        return $this->_iteratorCurrentPointer = 0;
    }

    // возвращает текущий элемент
    public function current()
    {
        return $this->_getData($this->_fileNames[$this->_iteratorCurrentPointer]);
    }

    // возвращает ключ текущего элемента
    public function key()
    {
        return $this->_fileNames[$this->_iteratorCurrentPointer];
    }

    // переходит к следующему элементу
    public function next()
    {
        return $this->_iteratorCurrentPointer++;
    }

    // проверяет, существует ли текущий элемент после выполнения мотода rewind или next
    public function valid()
    {
        if ($this->_iteratorCurrentPointer < $this->_iteratorTotalPoints)
            return true;
        return false;
    }
    /////////////////////////////
    ////////////////////////////////////////////////////////////////


}
