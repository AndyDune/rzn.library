<?
namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

class Pr extends HelperAbstract
{
    protected $_value = '';
    protected $_die   = false;

    public function __invoke($value, $is_die = false)
    {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
        if ($is_die)
            die();

        return $this;
    }

}
