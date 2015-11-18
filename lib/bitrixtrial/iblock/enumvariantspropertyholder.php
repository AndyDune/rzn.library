<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 18.11.2015                                     |
 * ----------------------------------------------------
 *
 * Выборка списка вариантов свойства инфоблока.
 * Медиатор ждет параметры:
 * Первый - символьный код свойтсва - обязательный
 * Втрокий - ключ к инфоблоку - это может быть его ID, CODE или путь в конфиге приложения
 * Третий - Какие значение списка вариантов использовать как ключ. По умолчанию ID
 *          может быть VALUE или XML_ID
 *
[YES] => Array
(
    [ID] => 1
    [PROPERTY_ID] => 5
    [VALUE] => да
    [DEF] => N // По умолчанию
    [SORT] => 500
    [XML_ID] => YES
    [TMP_ID] =>
    [EXTERNAL_ID] => YES
    [PROPERTY_NAME] => Спецпредложение
    [PROPERTY_CODE] => SPECIALOFFER
    [PROPERTY_SORT] => 100
)
 *
 $mediator->publish('getIBlockPropertyEnum', ['SPECIALOFFER', 3]);

 $mediator->publish('getIBlockPropertyEnum', ['SPECIALOFFER', 'furniture', 'VALUE']);

 mediator->publish('getIBlockPropertyEnum', ['SPECIALOFFER', 'infoblocks.ids.work_catalog_id', 'XML_ID']);
 *
 *
 * todo добавить кеширование
 */


namespace Rzn\Library\BitrixTrial\Iblock;

use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;
use Bitrix\Main\Loader;
use CIBlockPropertyEnum;
use CIBlock;

class EnumVariantsPropertyHolder implements ConfigServiceAwareInterface
{

    protected $config;

    public function __construct()
    {
        Loader::includeModule('iblock');
    }

    public function __invoke($params)
    {
        if (!isset($params[0]) or !isset($params[1])) {
            return null;
        }
        $code     = $params[0];
        $iblockId = $params[1];
        $useAsKey = (isset($params[2]))? $params[2] : 'ID';
        if (preg_match('|[^0-9]{1,}|', $iblockId, $a)) {
            if (preg_match('|\.|', $iblockId)) {
                $iblockId = $this->getConfigService()->getNested($iblockId);
            } else {
                $res = CIBlock::GetList(
                    [],
                    [
                        "CODE" => $iblockId
                    ], false
                )->Fetch();
                if ($res) {
                    $iblockId = $res['ID'];
                }
            }
        }

        if (!$iblockId) {
            return null;
        }

        $value = [];
        $dbRes = CIBlockPropertyEnum::GetList(
            ["SORT" => "ASC", "VALUE" => "ASC"],
            ['IBLOCK_ID' => $iblockId, 'CODE' => $code]
        );
        while ($some = $dbRes->Fetch()) {
            $value[$some[$useAsKey]] = $some;
        }

        return $value;

    }

    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {
        $this->config = $service;
    }

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->config;
    }

}