<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 04.08.14                                      
  * ----------------------------------------------------
  *
  * Обертка над АПИ Битрикса.
  * Необходима для корректрной работы со свойствами-флагами: Новинка, акция, лидер продаж, в наличии.
  *
  */


namespace Rzn\Library\BitrixTrial\Iblock;
use CIBlockPropertyEnum;
use Rzn\Library\Exception;
use CIBlockElement;

class EnumSingleValuePropertySetReset 
{
    protected $iblockId = null;

    protected $propertyCode = null;

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
     * Установить свойство-флаг.
     *
     * @param $id идентификатор элеметна.
     * @return mixed если указать число - сохранится именно оно
     */
    public function set($id, $value = true)
    {
        return $this->_process($id, $value);
    }

    /**
     * Снять свойство-флаг.
     *
     * @param $id идентификатор элеметна.
     * @return mixed
     */
    public function reset($id)
    {
        return $this->_process($id, null);
    }

    public function getEnumPropertyId()
    {
        $value = null;
        $propCode = $this->propertyCode;
        $some = CIBlockPropertyEnum::GetList(
            ["SORT"=>"ASC", "VALUE"=>"ASC"],
            ['IBLOCK_ID' => $this->iblockId, 'CODE' => $propCode]
        )->Fetch();
        if (isset($some['ID'])) {
            $value = $some['ID'];
        }
        return $value;
    }

    /**
     *  Выбрать все варианты свойства.
     *
     *  Пример элемента варианта
        (
            [ID] => 43
            [PROPERTY_ID] => 183
            [VALUE] => Неизвестный магазин
            [DEF] => Y // элемент используется по-умолчанию.
            [SORT] => 100
            [XML_ID] => wild
            [TMP_ID] =>
            [EXTERNAL_ID] => wild
            [PROPERTY_NAME] => Состояние магазина в системе
            [PROPERTY_CODE] => condition
            [PROPERTY_SORT] => 300
        )
     *
     * @param string $useAsKey Возможные варианты: ID, XML_ID, EXTERNAL_ID, PROPERTY_NAME, VALUE
     * @return array
     */
    public function getEnumPropertyVariants($useAsKey = 'ID')
    {
        $value = [];
        $propCode = $this->propertyCode;
        $dbRes = CIBlockPropertyEnum::GetList(
            ["SORT"=>"ASC", "VALUE"=>"ASC"],
            ['IBLOCK_ID' => $this->iblockId, 'CODE' => $propCode]
        );
        while ($some = $dbRes->Fetch()) {
            $value[$some[$useAsKey]] = $some;
        }

        return $value;
    }


    /**
     * Запуск.
     * В сыром виде этот код можно посмотреть здесь: local/event_handler_catalog.php (66 строка)
     *
     * @param $id
     * @param $value
     * @return mixed
     *
     *
     */
    protected function _process($id, $value)
    {
        if (!$this->propertyCode or !$this->iblockId) {
            throw new Exception('А надо запустить метод: setPropertyCode', 100);
        }

        $propCode = $this->propertyCode;
        if (is_bool($value)) {
            $value = null;
            $some = CIBlockPropertyEnum::GetList(
                ["SORT"=>"ASC", "VALUE"=>"ASC"],
                ['IBLOCK_ID' => $this->iblockId, 'CODE' => $propCode]
            )->Fetch();
            if (isset($some['ID'])) {
                $value = $some['ID'];
            }
        } else if (is_string($value)) {
            // Допустима передача XML_ID
            $variants = $this->getEnumPropertyVariants('XML_ID');
            if (array_key_exists($value, $variants)) {
                $value = $variants[$value]['ID'];
            }
        }
        return CIBlockElement::SetPropertyValues($id, $this->iblockId, $value, $propCode);
    }

} 