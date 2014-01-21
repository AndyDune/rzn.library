<?
namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;
use Rzn\Library\String\Explode;

class StringWithWhiteSpace extends HelperAbstract
{
    protected $_string = '';
    protected $_minLength = 1;

    protected $_class = 'ws-no-wrap';

    public function __invoke($string, $minLength = 1, $params = array())
    {
        $this->_string = $string;
        $this->_minLength = $minLength;

        $this->_process();

        return $this;
    }

    protected function _process()
    {
        $explode = new Explode($this->_string, ' ');
        $result_free = $explode->get();
        $result_len = array();
        $result = array();
        foreach($result_free as $key => $word)
        {
            $result_len[$key] = strlen($word);
        }

        $lastKeyResult = 0;
        foreach($result_free as $key => $word)
        {
            if ($result_len[$key] > $this->_minLength)
            {
                if (isset($result[$lastKeyResult]))
                    $lastKeyResult++;

                if ($lastKeyResult) $lastKeyResult++;
                $result[$lastKeyResult] = $word;
                continue;
            }
            if (!isset($result[$lastKeyResult]))
                $result[$lastKeyResult] = '';
            $result[$lastKeyResult] .= ' ' .  $word;
        }

        $res = '';
        foreach($result as $value)
        {
            $res .= '<span class="' . $this->_class . '">' . $value . '</span> ';
        }
        $this->_result = $res;
    }

}
