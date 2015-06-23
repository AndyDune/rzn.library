<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 14.04.2015                                      
  * ----------------------------------------------------
  *
  * Наблюдаю странное поведение метода Update
  * К примеру:
  *  $el = new \CIBlockElement;
  *  $el->Update(1040, array('NAME' => 'Сапоги CHIRUCA Iceland'));
  *  http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/update.php
  * Здесь не указан ключ PROPERTY_VALUES и как слудет из документации свойства стереться не должны, однако это не так.
  * Пользовательските свойтсва стираются.
  *
  * Однако в классе CIBlockElement есть метод UpdateList, логика в котором способна обновлять только указанные поля.
  *
  */


namespace Rzn\Library\BitrixTrial\Iblock\Element;
use Rzn\Library\Exception;
use CIBlockElement;

class Update extends CIBlockElement
{
    protected $filter = [];

    /**
     * Данные, которые являются контекстом для событий.
     * Передаются слушателям во время событий.
     * Возможные события:
     * iblock_BeforeElementUpdateList
     * iblock_AfterElementUpdateList
     *
     * @var mixed
     */
    protected $context = null;

    /**
     * Внедрение идентификатора элемента, который надо обновить.
     * @param $id
     */
    public function setId($id)
    {
        $this->filter['ID'] = $id;
    }

    /**
     * Внедрение идентификатора инфоблока элемента, который надо обновить.
     * @param $id
     */
    public function setIblockId($id)
    {
        $this->filter['IBLOCK_ID'] = $id;
    }


    /**
     * Внедрение контекста для событий, которые может генерить объект этого класса.
     * В любом формате, необходимомо для распознавания в каком именно случа произошло обновление.
     * Однако рекомендую массив.
     *
     *
     * @param $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * Перегружает метод UpdateList родителя для нормального обновления оргинальных полей инфоблока.
     *
     * @param $fields список полей для обновления.
     * @return bool|\CDBResult
     * @throws Exception
     */
    public function update($fields)
    {
        if (!count($this->filter)) {
            throw new Exception('Нужно хоть что-то указать в фильтре');
        }
        // todo возможна вставка событий на обновление
        return parent::UpdateList($fields, $this->filter);
    }
}