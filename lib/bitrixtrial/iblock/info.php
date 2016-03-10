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
use CIBlock;

class Info 
{
    protected $iblocks = [];

    public function getIblockIdWithCode($code)
    {
        if (array_key_exists($code, $this->iblocks)) {
            if (!$this->iblocks[$code]) {
                return null;
            }
            return $this->iblocks[$code]['ID'];
        }

        $this->iblocks[$code] = CIBlock::GetList([], [
            "CODE" => $code,
            'ACTIVE' => 'Y'
        ])->Fetch();

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

        $this->iblocks[$code] = CIBlock::GetList([], [
            "CODE" => $code,
            'ACTIVE' => 'Y'
        ])->Fetch();

        if (!$this->iblocks[$code]) {
            return null;
        }
        return $this->iblocks[$code]['VERSION'];
    }

}