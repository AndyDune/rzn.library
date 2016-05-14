<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 08.03.2016                                      
 * ----------------------------------------------------
 *
 * Объект класса хранится в сервисе: iblock_info
*/

namespace Rzn\Library\BitrixTrial\Iblock;
use Bitrix\Main\Loader;
use CIBlock;

class Info 
{
    protected $iblocks = [];

    protected $iblockInfo = [];

    public function __construct()
    {
        Loader::includeModule('iblock');
    }

    public function getIblockIdWithCode($code)
    {
        if (array_key_exists($code, $this->iblocks)) {
            if (!$this->iblocks[$code]) {
                return null;
            }
            return $this->iblocks[$code]['ID'];
        }

        $this->retrieveIblockInfoWithCode($code);

        if (!$this->iblocks[$code]) {
            return null;
        }
        return $this->iblocks[$code]['ID'];
    }

    public function getIblockVersionWithCode($code)
    {
        if (array_key_exists($code, $this->iblocks)) {
            if (!$this->iblocks[$code]) {
                return null;
            }
            return $this->iblocks[$code]['VERSION'];
        }

        $this->retrieveIblockInfoWithCode($code);

        if (!$this->iblocks[$code]) {
            return null;
        }
        return $this->iblocks[$code]['VERSION'];
    }

    /**
     * @param $code
     * @return Property
     */
    public function getIblockPropertyInfoWithCode($code)
    {
        if (array_key_exists($code, $this->iblockInfo)) {
            return $this->iblockInfo[$code];
        }

        $this->retrieveIblockInfoWithCode($code);

        if (!$this->iblocks[$code]) {
            return null;
        }
        $this->iblockInfo[$code] = new Property($this->iblocks[$code]['ID']);
        return $this->iblockInfo[$code];
    }


    protected function retrieveIblockInfoWithCode($code)
    {
        return $this->iblocks[$code] = CIBlock::GetList([], [
            "CODE" => $code,
            'ACTIVE' => 'Y'
        ])->Fetch();

    }


}