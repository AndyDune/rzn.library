<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dune
 * Date: 11.09.12
 * Time: 18:24
 *
 * Выборка из массива первого встречного значения.
 */
namespace Rzn\Library\Extract;
class ArrayFirstMeetValue
{
    protected $_array = array();
    protected $_keys = array();

    protected $_default = null;

    protected $_notEmpty = false;

    protected $_made   = false;
    protected $_isFind = false;
    protected $_result = null;

    /**
     * @param array $array массив с данными
     * @param array|string $keys ключи для поиска
     */
    public function __construct($array, $keys)
    {
        $this->_array = $array;
        if (is_array($keys))
        {
            $this->_keys = $keys;
        }
        else if (is_string($keys))
        {
            $this->_keys = explode(',', $keys);
        }
        else
            throw new \Exception('Не передан обязательный параметр', 1);
    }

    /**
     * Установка значения по умолчанию.
     *
     * @param null|string $value
     * @return ArrayFirstMeetValue
     */
    public function setDefault($value = null)
    {
        $this->_made = false;
        $this->_default = $value;
        return $this;
    }

    /**
     * Выбирать ли из массива значения, равные null или '' (пустая строка)
     *
     * По умолчанию пустые значения выбираются.
     *
     * @param bool $value
     * @return ArrayFirstMeetValue
     */
    public function setNotEmpty($value = true)
    {
        $this->_made = false;
        $this->_notEmpty = $value;
        return $this;
    }

    /**
     * Вернуть результат.
     * Если ничего не найдено возвращается значение по умолчанию.
     *
     * @return null|string|integer
     */
    public function get()
    {
        $this->find();
        return $this->_result;
    }

    /**
     * Запуск процесса поиска.
     * При множественном вызове метода повторной отработки не происходит.
     *
     * @return bool Флаг успеха поиска.
     */
    public function find()
    {
        if ($this->_made)
            goto before_return;
        $this->_made   = true;
        $this->_isFind = false;
        $this->_result = $this->_default;
        foreach($this->_keys as $value)
        {
            $value = trim($value);
            if (!$value or !isset($this->_array[$value]))
                continue;
            if ($this->_notEmpty
                and
                (
                     null === $this->_array[$value]
                     or
                     (is_string($this->_array[$value]) and !$this->_array[$value])
                )
               )
                continue;
            $this->_result = $this->_array[$value];
            $this->_isFind = true;
            break;
        }
        before_return:
        return $this->_isFind;
    }
}
