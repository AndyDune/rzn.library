<?
namespace Rzn\Library\Component;

abstract class HelperAbstract
{
    protected $_result = '';

    public function get()
    {
        return $this->_result;
    }

    public function __toString()
    {
        return $this->get();
    }

    public function pr($value = null)
    {
        if (is_null($value))
            $value = $this->_result;
        ob_start();
        echo '<pre>';
        print_r($value);
        echo '</pre>';
        return ob_get_clean();
    }


}