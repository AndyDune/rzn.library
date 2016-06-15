<?php
/**
 * ----------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>   |
 * | Сайт: www.rznw.ru                           |
 * | Телефон: +7 (4912) 51-10-23                 |
 * | Дата: 15.06.2016                            |
 * -----------------------------------------------
 *
 */


namespace Rzn\Library\Tests;
use PHPUnit_Framework_TestCase;

class ArrayAccessTest extends PHPUnit_Framework_TestCase
{
    public function testIt()
    {
        $array = ['1', 'two' => 2, false];
        $object = new \Rzn\Library\ArrayContainer($array);
        $count = 0;
        foreach($object as $key => $value) {
            $count++;
            $this->assertEquals($array[$key], $value);
        }

        $this->assertEquals(3, $count);
        $this->assertEquals(3, count($object));
    }
}