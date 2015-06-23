<?
/**
 * Упрощатель работы с api битрикса.
 *
 */
namespace Rzn\Library\BitrixTrial\Order;
class Props
{
    protected $_propertiesCodeKey = array();

    public function __construct()
    {

    }

    /**
     *
     *
        Возвращает массив с параметрами свойства
            ID	Код свойства заказа.
            PERSON_TYPE_ID	Тип плательщика.
            NAME	Название свойства.
            TYPE	Тип свойства. Допустимые значения:
            CHECKBOX - флаг,
            TEXT - строка текста,
            SELECT - выпадающий список значений,
            MULTISELECT - список со множественным выбором,
            TEXTAREA - многострочный текст,
            LOCATION - местоположение,
            RADIO - переключатель.
            REQUIED	Флаг (Y/N) обязательное ли поле.
            DEFAULT_VALUE	Значение по умолчанию.
            SORT	Индекс сортировки.
            USER_PROPS	Флаг (Y/N) входит ли это свойство в профиль покупателя.
            IS_LOCATION	Флаг (Y/N) использовать ли значение свойства как местоположение покупателя для расчёта стоимости доставки (только для свойств типа LOCATION)
            PROPS_GROUP_ID	Код группы свойств.
            SIZE1	Ширина поля (размер по горизонтали).
            SIZE2	Высота поля (размер по вертикали).
            DESCRIPTION	Описание свойства.
            IS_EMAIL	Флаг (Y/N) использовать ли значение свойства как E-Mail покупателя.
            IS_PROFILE_NAME	Флаг (Y/N) использовать ли значение свойства как название профиля покупателя.
            IS_PAYER	Флаг (Y/N) использовать ли значение свойства как имя плательщика.
            IS_LOCATION4TAX	Флаг (Y/N) использовать ли значение свойства как местоположение покупателя для расчёта налогов (только для свойств типа LOCATION)
            CODE	Мнемонический код свойства.     *
     * Или, если такого свойства нет - возвращается null.
     *
     * @param $code
     * @return null|array
     */
    public function getPropertyWithCode($code)
    {
        if (array_key_exists($code, $this->_propertiesCodeKey))
            goto end;
        $this->_propertiesCodeKey[$code] = \CSaleOrderProps::GetList(array(), array('CODE' => $code))->Fetch();
        end:
        return $this->_propertiesCodeKey[$code];
    }


}