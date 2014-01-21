<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 02.08.13
 * Time: 16:41
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

class ShowError extends HelperAbstract
{
    public function __invoke($value, $aliases = array())
    {
        if (isset($aliases[$value]))
        {
            $value = $aliases[$value];
        }
        ob_start();
        ShowError($value);
        $this->_result = ob_get_clean();
        return $this;
    }

}