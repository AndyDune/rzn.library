<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 26.06.13
 * Time: 11:39
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\BitrixTrial\Iblock\Section;


class Property
{
    protected $_sectionId;
    protected $_iblockId = null;
    public function __construct($sectionId, $iblockId)
    {
        $this->_sectionId = $sectionId;
        $this->_iblockId = $iblockId;
    }

    public function getUserPropertyWithCode($code)
    {
        $name = "UF_" . $code;
        $result = \CIBlockSection::GetList(Array(), Array('IBLOCK_ID' => $this->_iblockId, "ID"=> $this->_sectionId), false, Array($name))->Fetch();
        if (isset($result[$name]))
            return $result[$name];
        return null;
    }
}