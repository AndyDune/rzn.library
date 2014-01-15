<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 11.09.13
 * Time: 14:40
 *
 * Коасс для строительства строки.
 */

namespace Rzn\Library\Db;
use Rzn\Library\Exception;
use Rzn\Library\Registry;


class BuildSaveDataString
{
    /**
     * Карта и форматы данных.
     * Ключ - имя полу
     * Значение :
     * array(
            'values' => array()  // Дозволенные значений, используется совместно с default
     *      'default' => // значение по умолчанию - для разных ситуаций
     *      'type' => 'int|array|string' // Фоомат даных
     *                  int - приводится к целому чисду
     *                  array - если масив, то сериализуется - иначе хначение запихивается в массив, который сериализуется
     *      'len' => максимальная длина, обрудается ели превышает
     *
     * )
     *
     * @var array
     */
    protected $_fields = array();

    protected $_data = array();

    /**
     * @var \CDatabase
     */
    protected $_db;

    public function __construct($format = null)
    {
        if (is_array($format))
            $this->setFieldsFormat($format);
        $reg = Registry::getInstance();
        /** @var \CDatabase $db */
        $db = $reg->getGlobal('DB');
        $this->_db = $db;
    }

    public function setFieldsFormat($format)
    {
        $this->_fields = $format;
        return $this;
    }

    public function setData($data)
    {
        if (is_array($data))
            $this->_data = $data;
        return $this;
    }

    public function getInsert($data = null)
    {
        if ($data)
            $this->setData($data);
        $dataToSave = $this->_prepareSaveData();
        $strFields = ' (';
        $strValues = '(';
        if (!count($dataToSave))
            return '';
        $semicolon = '';
        foreach($dataToSave as $key => $value)
        {
            $strFields .= $semicolon . '`' . $key . '`';
            $strValues .= $semicolon . $value;
            $semicolon = ', ';
        }
        $strFields .= ')';
        $strValues .= ') ';
        return $strFields . ' VALUES ' . $strValues;
    }

    public function getUpdate($data = null)
    {
        if ($data)
            $this->setData($data);
        $dataToSave = $this->_prepareSaveData();
        $str = ' SET ';
        if (!count($dataToSave))
            return '';
        $semicolon = '';
        foreach($dataToSave as $key => $value)
        {
            $str .= $semicolon . '`' . $key . '` = '. $value;
            $semicolon = ', ';
        }
        return $str . ' ';
    }


    protected function _prepareSaveData()
    {
        $data = $this->_data;
        $config = $this->_fields;
        $dataToSave = array();
        foreach($data as $key => $value)
        {
            $quote = true;
            if (array_key_exists($key, $config))
            {
                if (isset($config[$key]['type']))
                {
                    switch($config[$key]['type'])
                    {
                        case 'int':
                            $quote = false;
                            $value = (int)$value;
                            break;
                        case 'array':
                            if (is_array($value))
                                $value = serialize($value);
                            else
                                $value = serialize(array($value));
                            break;
                        case 'string':
                        default:
                            if (isset($config[$key]['minlen']) )
                            {
                                $len = strlen($value);
                                if ($len < $config[$key]['minlen'])
                                    throw new Exception('Поле ' . $key .' должно быть минимум длинной ' . $config[$key]['minlen'] . ' символ(ов)', 1);

                            }
                            if (isset($config[$key]['len']) )
                            {
                                $len = strlen($value);
                                if ($len > $config[$key]['len'])
                                    $value = substr($value, 0, $len - 1);
                            }
                    }

                }


                if (isset($config[$key]['values']) and isset($config[$key]['default']))
                {
                    if (in_array($value, $config[$key]['values']))
                        $dataToSave[$key] = $value;
                    else
                        $dataToSave[$key] = $config[$key]['default'];
                }
                else
                {
                    $dataToSave[$key] = $value;
                }
                if ($quote)
                    $dataToSave[$key] = '"' . $this->_db->ForSql($dataToSave[$key]) . '"';


            }
        }
        return $dataToSave;

    }


}