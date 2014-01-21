<?php
/**
 *
 *
 */
namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

class ArrayExtractValuesWithPrefix extends HelperAbstract
{
    protected $_data = [];

    public function __invoke($prefix = null, $array = [], $params = [])
    {
        if (!$prefix)
            goto end;

        $processed = [];

        if (isset($params['delete']) and $params['delete'])
            $delete = true;
        else
            $delete = false;

        foreach($array as $key => $value)
        {
            $pos = strrpos($key, $prefix);
            if ($pos === 0)
            {
                if ($delete)
                    $key = substr($key, strlen($prefix));
                $processed[$key] = $value;
            }
        }

        $this->_data = $processed;
        end:
        return $this;
    }

    public function get()
    {
        return $this->_data;
    }
}
