<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 24.07.13
 * Time: 18:34
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\Component\Helper;


use Rzn\Library\Component\HelperAbstract;

class GetIblockElementPropertyValueWithCode extends HelperAbstract
{
    public function __invoke($id, $code)
    {
        $this->_result = '';
        $element = \CIBlockElement::GetByID($id)->Fetch();
        if ($element)
        {
            $props = \CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], array(), array('CODE' => $code))->Fetch();
            //echo $this->pr($props);
            if (isset($props['VALUE']))
                $this->_result = $props['VALUE'];
        }

        return $this;
    }

}