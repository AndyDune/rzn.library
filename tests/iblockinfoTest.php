<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 08.03.2016                                      
 * ----------------------------------------------------
*/

namespace Rzn\Library\Tests;
use Rzn\Library\Registry;
use Bitrix\Main\Loader;
use PHPUnit_Framework_TestCase;
use CIBlock;

class IblockInfoTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobals = false;
    public function setUp()
    {
        Loader::includeModule('iblock');
    }

    public function testGetId()
    {
        $res = CIBlock::GetList([], [
            'ACTIVE' => 'Y'
        ]);

        $iblockToUseInTest = null;

        while($row = $res->Fetch()) {
            if ($row['CODE']) {
                $iblockToUseInTest = $row;
                break;
            }
        }
        $this->assertTrue(is_array($iblockToUseInTest), 'Нет ни одного инфоблока с описанным кодом');

        $sm = Registry::getServiceManager();
        /** @var \Rzn\Library\BitrixTrial\Iblock\Info $iblockInfo */
        $iblockInfo = $sm->get('iblock_info');
        $id = $iblockInfo->getIblockIdWithCode('this_wrong');
        $this->assertEquals(null, $id);

        $id = $iblockInfo->getIblockIdWithCode($iblockToUseInTest['CODE']);
        $this->assertEquals($iblockToUseInTest['ID'], $id);

        $iblockInfo->getIblockIdWithCode($iblockToUseInTest['CODE']);

    }
}