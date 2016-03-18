<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 12.03.2015
 * ----------------------------------------------------
 *
 * Класс вспомогатор для загрузки файлов для соответствующего множественного свойсва инфоблока.
 *
 * Пример использования при сохранении:
    $saveMorepicture = new Rzn\Library\BitrixTrial\Iblock\MultiFileProperty($config->getNested('infoblocks.ids.shops'));
    $saveMorepicture->setPropertyCode('more_picture')
                    ->setElementId($ID)
                    ->setDescriptionArray($_POST['description']['more_picture'])
                    ->setSortArray($_POST['order']['more_picture'])
                    ->setDeleteArray($_POST['delete_image']['more_picture'])
                    ->setFilesArray($filesMorePicture, 'VALUE')
                    ->save()
    ;

 * Пример использования при отображении свойства:
    $saveMorepicture = new Rzn\Library\BitrixTrial\Iblock\MultiFileProperty($config->getNested('infoblocks.ids.shops'));
    $saveMorepicture->setPropertyCode('more_picture');
    $saveMorepicture->setElementId($ID);
    $morePictures = $saveMorepicture->extractSorted();
 *
 *  $morePictures - отсортированный массив, который и нужно использоввать для отображенгия
 */


namespace Rzn\Library\BitrixTrial\Iblock;

use Rzn\Library\Exception;
use CIBlockElement;

class MultiFileProperty
{
    protected $iblockId = null;

    protected $propertyCode = null;
    protected $elementId = null;

    /**
     * Массив существующих данных
     * Важно для принятия решения обновлять ли добавлять.
     *
     * null - данные не опрашивались - нужно запросить перед сохранением
     *
     * @var null|array
     */
    protected $existDataArray = null;

    protected $dataToSaveArray = [];
    protected $deleteToSaveArray = [];
    protected $sortToSaveArray = [];

    protected $sortToSaveDefault = null;

    protected $descriptionToSaveArray = [];


    protected $maxCount = null;

    /**
     * Разделитель, который используется в поле description для разделение значения сортировки и описания.
     *
     * @var string
     */
    protected $descriptionSeparatorAfterOrder = '::';

    /**
     * Ключ из массива, который мы получаем методом getProperty
     * По этому ключу мы будем делать вывод новый это файл или существующий.
     *
     * Используется 2 ключа VALUE и PROPERTY_VALUE_ID
     *
     * @var string
     */
    protected $keyToFindExistFiles = 'PROPERTY_VALUE_ID';

    public function __construct($iblockId)
    {
        $this->iblockId = $iblockId;
    }

    /**
     * Внедрить код нужного свойства.
     *
     * @param $code
     * @return $this
     */
    public function setPropertyCode($code)
    {
        $this->propertyCode = $code;
        return $this;
    }

    /**
     * Внедрить id элемента
     *
     * @param $id
     * @return $this
     */
    public function setElementId($id)
    {
        $this->elementId = $id;
        return $this;
    }


    public function setMaxImages($count)
    {
        $this->maxCount = $count;
        return $this;
    }

    /**
     *
     *
     * Каждый элемента содержит следующее
     * [ID] => 205
     * [TIMESTAMP_X] => 2015-03-10 15:04:21
     * [IBLOCK_ID] => 12
     * [NAME] => Дополнительные фотографии
     * [ACTIVE] => Y
     * [SORT] => 500
     * [CODE] => more_picture
     * [DEFAULT_VALUE] =>
     * [PROPERTY_TYPE] => F
     * [ROW_COUNT] => 1
     * [COL_COUNT] => 30
     * [LIST_TYPE] => L
     * [MULTIPLE] => Y
     * [XML_ID] => more_picture
     * [FILE_TYPE] => jpg, gif, bmp, png, jpeg
     * [MULTIPLE_CNT] => 5
     * [TMP_ID] =>
     * [LINK_IBLOCK_ID] => 0
     * [WITH_DESCRIPTION] => N
     * [SEARCHABLE] => N
     * [FILTRABLE] => N
     * [IS_REQUIRED] => N
     * [VERSION] => 2
     * [USER_TYPE] =>
     * [USER_TYPE_SETTINGS] =>
     * [HINT] =>
     * [PROPERTY_VALUE_ID] => 350
     * [VALUE] => 2999
     * [DESCRIPTION] =>
     * [VALUE_ENUM] =>
     * [VALUE_XML_ID] =>
     * [VALUE_SORT] =>
     * @param array $order
     * @return array
     */
    public function getProperty($order = [])
    {
        if (!is_array($order)) {
            $order = [$order => 'ASC'];
        }
        // Элемент не указан, значит новый - свойств нет
        if (!$this->elementId) {
            return [];
        }
        $props = CIBlockElement::GetProperty($this->iblockId, $this->elementId, $order, ["CODE" => $this->propertyCode]);
        $results = [];
        while ($prop = $props->Fetch()) {
            if ($prop['VALUE']) {
                $results[] = $prop;
            }
        }
        $this->existDataArray = $results;
        return $results;
    }

    /**
     * Происходит пересортировка согласно данным из поля описания
     *
     *
     */
    public function extractSorted()
    {
        if ($this->existDataArray === null) {
            $this->getProperty();
        }
        $results = [];
        foreach ($this->existDataArray as $value) {
            $sort = null;
            if ($value['DESCRIPTION']) {
                $parts = explode($this->descriptionSeparatorAfterOrder, $value['DESCRIPTION']);
                if (count($parts) > 1) {
                    $sort = array_shift($parts);
                    if (!$this->isNumber($sort)) {
                        $sort = null;
                    }
                    // Удаляем за описания значение сортировки
                    $value['DESCRIPTION'] = implode($this->descriptionSeparatorAfterOrder, $parts);
                }
            }
            $value['VALUE_SORT'] = $value['ORDER'] = $sort;
            array_unshift($results, $value);
        }

        // Теперь сама сортировка
        usort($results, function ($a, $b) {
            if ($a['VALUE_SORT'] === null or $b['VALUE_SORT'] === null or $a['VALUE_SORT'] == $b['VALUE_SORT']) {
                return 0;
            }
            return ($a['VALUE_SORT'] < $b['VALUE_SORT']) ? -1 : 1;
        });

        return $results;
    }

    /**
     * Внедрение данных для сохранения.
     *
     *
     * @param $data есл ключем явл. число - запись существует - нужно обновить, начинается с символа - новое
     * @param string $key имя ключа, который будет использоваться для идентификации существующей записи
     */
    public function setFilesArray($data, $key = 'PROPERTY_VALUE_ID')
    {
        if (!in_array($key, ['PROPERTY_VALUE_ID', 'VALUE'])) {
            throw new Exception('Передан неверный ключ $key. Допустимые: PROPERTY_VALUE_ID, VALUE');
        }
        $this->dataToSaveArray = $data;
        $this->keyToFindExistFiles = $key;
        return $this;
    }

    public function setDescriptionArray($array)
    {
        $this->descriptionToSaveArray = $array;
        return $this;
    }

    /**
     * Внедрение сортировки
     * @param $array
     * @return $this
     */
    public function setSortArray($array, $default = null)
    {
        $this->sortToSaveArray = $array;
        $this->sortToSaveDefault = $default;
        return $this;
    }

    public function setDeleteArray($array)
    {
        $this->deleteToSaveArray = $array;
        return $this;
    }

    /**
     * Возврат разделителя для помещения значения сортировки в поле описания.
     * Помещать значение сортировки в описание можно за пределами этого инструмента.
     *
     * @return string
     */
    public function getSeparatorForOrderValue()
    {
        return $this->descriptionSeparatorAfterOrder;
    }

    /**
     *
     * 205] => Array
     * (
     * [350] => Array
     * (
     * [VALUE] => Array
     * (
     * [name] => 2013-un-en-iyi-oyunlari_69530_b.jpg
     * [type] => image/jpeg
     * [tmp_name] => D:\OpenServer\userdata\temp\php1022.tmp
     * [error] => 0
     * [size] => 55421
     * [description] =>
     * )
     *
     * [DESCRIPTION] =>
     * )
     *
     * [351] => Array
     * (
     * [VALUE] => Array
     * (
     * [name] =>
     * [type] =>
     * [tmp_name] =>
     * [error] => 4
     * [size] => 0
     * [del] => Y
     * )
     *
     * )
     *
     * [n1] => Array
     * (
     * [VALUE] => Array
     * (
     * [name] => colored_elephants.jpg
     * [type] => image/jpeg
     * [tmp_name] => D:\OpenServer\userdata\temp\php1023.tmp
     * [error] => 0
     * [size] => 142029
     * )
     * )
     *
     * [n2] => Array
     * (
     * [VALUE] => Array
     * (
     * [name] => wallpaper_destiny_07.jpg
     * [type] => image/jpeg
     * [tmp_name] => D:\OpenServer\userdata\temp\php1034.tmp
     * [error] => 0
     * [size] => 10413
     * )
     * )
     *
     * )
     *
     * )
     */
    public function save()
    {
        if (!$this->elementId or !$this->iblockId or !$this->propertyCode) {
            throw new Exception('Не указан обязательный параметр для сохранения: elementId, iblockId, propertyCode');
        }

        if ($this->existDataArray === null) {
            $this->getProperty();
        }

        $existData = [];
        // Выбираем существующие записи с обозначением унтересующего ключа.
        if (count($this->existDataArray)) {
            foreach ($this->existDataArray as $run) {
                $existData[$run[$this->keyToFindExistFiles]] = $run;
            }
        }

        $saveData = [];
        $currentNewNumber = 0;
        $count = 0;
        // Сохраняемых данных
        foreach ($this->dataToSaveArray as $key => $value) {
            if ($this->maxCount and $count > $this->maxCount) {
                break;
            }
            $item = ['VALUE' => $value, 'DESCRIPTION' => ''];
            // Завяка на удаление может быть в масиве данных или в отдельно обозначаемом массиве
            if (isset($value['del'])
                or (isset($this->deleteToSaveArray[$key]) and $this->deleteToSaveArray[$key])
            ) {
                $item['VALUE']['del'] = 'Y';
            } else {
                // Считам только те, которые не удаляем
                $count++;
            }

            // Описание в массиве даннх имеет приоритет (о сортировке тоже нужно обеспокоиться извне)
            if (isset($item['VALUE']['DESCRIPTION'])) {
                $item['DESCRIPTION'] = $item['VALUE']['DESCRIPTION'];
            } else {
                if (isset($this->descriptionToSaveArray[$key]) and $this->descriptionToSaveArray[$key]) {
                    $item['DESCRIPTION'] = $this->descriptionToSaveArray[$key];
                }

                // Прикрепляем сортировку
                if (isset($this->sortToSaveArray[$key]) and (int)$this->sortToSaveArray[$key]) {
                    $item['DESCRIPTION'] = (int)$this->sortToSaveArray[$key]
                        . $this->descriptionSeparatorAfterOrder
                        . $item['DESCRIPTION'];
                } else if ($this->sortToSaveDefault) {
                    $item['DESCRIPTION'] = $this->sortToSaveDefault
                        . $this->descriptionSeparatorAfterOrder
                        . $item['DESCRIPTION'];
                }
            }

            $item['VALUE']['description'] = $item['DESCRIPTION'];
            //pr($value);
            if ($this->isNumber($key)) {
                // Обновлять данные нужно обязательно с цифрой, которая хранится за ключем PROPERTY_VALUE_ID
                if (isset($existData[$key])) {
                    $saveData[$existData[$key]['PROPERTY_VALUE_ID']] = $item;
                }
            } else {
                $saveData['n' . $currentNewNumber] = $item;
                $currentNewNumber++;
            }
        }
        //pr($saveData); die();
        return CIBlockElement::SetPropertyValues($this->elementId, $this->iblockId, $saveData, $this->propertyCode);
    }

    /**
     * Вспомогательный метод. Проверка а число ли.
     *
     * @param $value
     * @return bool
     */
    public function isNumber($value)
    {
        if (preg_match('|^[0-9]+$|', $value)) {
            return true;
        }
        return false;
    }

    /**
     * Вспомогательный метод. Нормализация массива $_FILE
     *
     * При загрузке файлов в массиве получаем массив, который не совсем удобно использовать:
     * Метод группирует информацию о файлах не по типу, а по принадлежности к файлу.
     *
     * Информация вида: <input type="file" name="image[more_picture][<КОД>]" >

        pr($_FILES);
        $filesMorePicture = $filesArrayNormalize($_FILES['image']);
        pr($filesMorePicture); die();

     *
    [image] => Array
    (
        [name] => Array
        (
            [more_picture] => Array
            (
                [2999] =>
                [3005] =>
                [3003] =>
                [3006] =>
                [{id}] =>
                [new_1426239517396] =>
            )
        )

        [type] => Array
        (
            [more_picture] => Array
            (
                [2999] =>
                [3005] =>
                [3003] =>
                [3006] =>
                [{id}] =>
                [new_1426239517396] =>
            )
        )

        [tmp_name] => Array
        (
            [more_picture] => Array
            (
                [2999] =>
                [3005] =>
                [3003] =>
                [3006] =>
                [{id}] =>
                [new_1426239517396] =>
            )
        )

        [error] => Array
        (
            [more_picture] => Array
            (
                [2999] => 4
                [3005] => 4
                [3003] => 4
                [3006] => 4
                [{id}] => 4
                [new_1426239517396] => 4
            )
        )

        [size] => Array
        (
            [more_picture] => Array
            (
                [2999] => 0
                [3005] => 0
                [3003] => 0
                [3006] => 0
                [{id}] => 0
                [new_1426239517396] => 0
            )

        )

    )
     *
     *
     * Стало:
     *
    Array
    (
    [more_picture] => Array
    (
        [2999] => Array
        (
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] => 4
            [size] => 0
        )

        [3005] => Array
        (
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] => 4
            [size] => 0
        )

        [3003] => Array
        (
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] => 4
            [size] => 0
        )

        [3006] => Array
        (
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] => 4
            [size] => 0
        )

        [{id}] => Array
        (
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] => 4
            [size] => 0
        )

        [new_1426239517396] => Array
        (
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] => 4
            [size] => 0
        )

    )

    )
     *
     *
     * @param $files
     * @return array
     */
    public function filesArrayNormalize($files)
    {
        $result = array();
        foreach ($files as $nameOption => $array1) {
            foreach ($array1 as $id => $array2) {
                foreach ($array2 as $nameField => $value) {
                    if (!isset($result[$id])) {
                        $result[$id] = array();
                    }
                    $result[$id][$nameField][$nameOption] = $value;
                }
            }
        }
        return $result;
    }

}