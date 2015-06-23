<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 03.06.13
 * Time: 16:15
 *
 * Строит правильный массив из значения свойства, которое битрикс дает по умолчанию.
 *
 *
 * Каждый возврашаемый элемент имет формат:
 * Array
(
    [ID] => 1323
    [TIMESTAMP_X] => 03.06.2013 15:53:35
    [MODULE_ID] => iblock
    [HEIGHT] => 1773
    [WIDTH] => 2365
    [FILE_SIZE] => 1536436
    [CONTENT_TYPE] => image/png
    [SUBDIR] => iblock/a83
    [FILE_NAME] => a8345fa8a5cb1b3e235890534e27f996.png
    [ORIGINAL_NAME] => DSCN0484_clipped_rev_1.png
    [DESCRIPTION] =>
    [HANDLER_ID] =>
    [~src] =>
    [SRC] => /upload/iblock/a83/a8345fa8a5cb1b3e235890534e27f996.png
)
 *
 */

namespace Rzn\Library\BitrixTrial\Iblock\Image;


class GetFileArrayFromProperty
{
    protected $_result = array();

    public function __construct($data, $propertyName)
    {
        $result = array();
        if(isset($data["PROPERTIES"][$propertyName]["VALUE"]) && is_array($data["PROPERTIES"][$propertyName]["VALUE"]))
        {
            foreach($data["PROPERTIES"][$propertyName]["VALUE"] as $FILE)
            {
                $FILE = \CFile::GetFileArray($FILE);
                if(is_array($FILE))
                    $result[] = $FILE;
            }
        }
        $this->_result = $result;

    }

    public function get()
    {
        return $this->_result;
    }
}