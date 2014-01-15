<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dune
 * Date: 28.08.12
 * Time: 8:40
 * Извлекает номера телефонов из строки.
 *
 * Нормер телефона кроме цифр и плюсика в начале может содержать пробелы, скобки, дефисы.
 * Номер может быть введен не полностью, тогда он автоматически дополняется до полного формата.
 * Номер может содерать или нет код страны.
 * Код страны может быть как 8 так и +7
 */
namespace Rzn\Library\Extract;
class PhoneNumbers
{
    protected $_string = '';
    protected $_phones = array();
    protected $_defaultCity = '4912';
    protected $_cityNumberLength = 6;

    protected $_result = null;

    public function __construct($string, $defaultCity = null)
    {
        $this->_result = null;
        $this->_string = $string;
        if (null !== $defaultCity)
        {
            $this->_defaultCity = $defaultCity;
            $this->_cityNumberLength = 10 - strlen($defaultCity);
        }
    }

    public function count()
    {
        $result = $this->get();
        return count($result);
    }

    public function get()
    {
        if ($this->_result !== null)
            return $this->_result;
        $string = str_replace(array(' ', '-', '(', ')'), '', $this->_string);
        $res = array();
        $result = array();
        // Неполные номера
        preg_match_all('#(?:[^0-9]+|^)([0-9]{' . $this->_cityNumberLength . '})(?:[^0-9]+|$)#', $string, $res);
        if (count($res[1]))
        {
            foreach ($res[1] as $value)
            {
                $result[] = $this->_defaultCity . $value;
            }
        }
        // Полные номера
        preg_match_all('#(?:[^0-9]+|^)(?:\+7|8|7)*([0-9]{10})(?:[^0-9]+|$)#', $string, $res);
        if (count($res[1]))
        {
            $result = array_merge($result, $res[1]);
        }
        return $this->_result = $result;
    }
}
