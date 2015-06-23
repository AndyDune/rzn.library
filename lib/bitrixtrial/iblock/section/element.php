<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 26.06.13
 * Time: 11:33
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\BitrixTrial\Iblock\Section;


class Element
{
    protected $_elementId = null;
    public function __construct($elementId)
    {
        $this->_elementId = $elementId;
    }

    public function getSections()
    {
        $groups = \CIBlockElement::GetElementGroups($this->_elementId);
        $newGroups = array();
        while($group = $groups->Fetch())
            $newGroups[] = $group;
        return $newGroups;

    }

    /**
     * Выборка полных веток разделов элементов.
     * Каждый узел ветки сордержит данные:
     * [0] => Array
        (
            [ID] => 6
            [~ID] => 6
            [TIMESTAMP_X] => 2013-07-11 11:45:37
            [~TIMESTAMP_X] => 2013-07-11 11:45:37
            [MODIFIED_BY] => 1
            [~MODIFIED_BY] => 1
            [DATE_CREATE] => 2013-02-06 12:02:27
            [~DATE_CREATE] => 2013-02-06 12:02:27
            [CREATED_BY] => 1
            [~CREATED_BY] => 1
            [IBLOCK_ID] => 3
            [~IBLOCK_ID] => 3
            [IBLOCK_SECTION_ID] =>
            [~IBLOCK_SECTION_ID] =>
            [ACTIVE] => Y
            [~ACTIVE] => Y
            [GLOBAL_ACTIVE] => Y
            [~GLOBAL_ACTIVE] => Y
            [SORT] => 0
            [~SORT] => 0
            [NAME] => Рыбалка
            [~NAME] => Рыбалка
            [PICTURE] =>
            [~PICTURE] =>
            [LEFT_MARGIN] => 19
            [~LEFT_MARGIN] => 19
            [RIGHT_MARGIN] => 70
            [~RIGHT_MARGIN] => 70
            [DEPTH_LEVEL] => 1
            [~DEPTH_LEVEL] => 1
            [DESCRIPTION] =>
            [~DESCRIPTION] =>
            [DESCRIPTION_TYPE] => html
            [~DESCRIPTION_TYPE] => html
            [SEARCHABLE_CONTENT] => РЫБАЛКА

            [~SEARCHABLE_CONTENT] => РЫБАЛКА

            [CODE] => fishing
            [~CODE] => fishing
            [XML_ID] => 2
            [~XML_ID] => 2
            [TMP_ID] =>
            [~TMP_ID] =>
            [DETAIL_PICTURE] =>
            [~DETAIL_PICTURE] =>
            [SOCNET_GROUP_ID] =>
            [~SOCNET_GROUP_ID] =>
            [LIST_PAGE_URL] => /catalog/
            [~LIST_PAGE_URL] => /catalog/
            [SECTION_PAGE_URL] => /catalog/fishing/
            [~SECTION_PAGE_URL] => /catalog/fishing/
            [IBLOCK_TYPE_ID] => catalog
            [~IBLOCK_TYPE_ID] => catalog
            [IBLOCK_CODE] => furniture
            [~IBLOCK_CODE] => furniture
            [IBLOCK_EXTERNAL_ID] => citimall
            [~IBLOCK_EXTERNAL_ID] => citimall
            [EXTERNAL_ID] => 2
            [~EXTERNAL_ID] => 2
        )
     *
     * @return array
     */
    public function getSectionsTree()
    {
        $directGroups = $this->getSections();
        $path = array();
        foreach( $directGroups as $key => $directGroup)
        {
            $rsPath = \GetIBlockSectionPath($directGroup["IBLOCK_ID"], $directGroup["ID"]);
            $sub = array();
            while($arPath = $rsPath->GetNext())
            {
                $sub[] = $arPath;
            }
            $path[] = $sub;

        }
        return $path;

    }

}