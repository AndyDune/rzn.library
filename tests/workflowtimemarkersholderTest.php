<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 20.03.2016                                      
 * ----------------------------------------------------
*/

namespace Rzn\Library\Tests;
use Rzn\Library\Registry;
use PHPUnit_Framework_TestCase;


class WorkflowTimeMarkersHolderTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobals = false;

    public function testThis()
    {
        $sm = Registry::getServiceManager();
        /** @var \Rzn\Library\Mediator\Mediator $mediator */
        $mediator = $sm->get('mediator');
        $mediator->publish('setTimeMarker', 'test_1');
        $result = $mediator->publish('setTimeMarker', 'test_2', ['print' => 'string', 'set_print' => true]);
        $this->assertEquals(1, preg_match('|test_1|', $result));
        $this->assertEquals(1, preg_match('|test_2|', $result));
    }
}