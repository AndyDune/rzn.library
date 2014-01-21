<?php
/**
 * Выводи на печать первое всречное непустое значение из массива ($array).
 * Перебирается входной массив ключей $keys
 *
 */
namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

class FirstExistValueInArray extends HelperAbstract
{
    private $_keys = [];
    private $_array = [];
    public function __invoke($array = null, $keys = null, $default = '')
    {
        $this->_result = $default;
        if ($keys !== null)
        {
            if (!is_array($keys))
                $keys = [$keys];
            $this->_keys = $keys;
        }
        if ($array !== null)
        {
            $this->_array = $array;
            $this->_buildData();
        }
        return $this;
    }

    public function setData($data)
    {
        $this->_array = $data;
        return $this;
    }
    
    protected function _buildData()
    {
        $keys = $this->_keys;
        $array = $this->_array;
        foreach($keys as $key)
        {
            if (isset($array[$key]) and $array[$key])
            {
                $this->_result = $array[$key];
                break;
            }
        }
    }
}



