<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 04.12.2015                                     |
 * ----------------------------------------------------
 *
 */


namespace Rzn\Library\Tests\Format;
use PHPUnit_Framework_TestCase;
use Rzn\Library\Format\ArrayModification as ArrayModificationTest;

class ArrayModification extends PHPUnit_Framework_TestCase
{
    public function testSetGet()
    {
        $data = [
            'id' => 13,
        ];
        $my = new ArrayModificationTest($data);
        // Тестируем arrayAccess
        $this->assertEquals($my['id'], 13);

        $my['id'] = 14;
        $this->assertEquals($my['id'], 14);
    }

    public function testDeleteKey()
    {
        $data = [
            'id' => 13,
            'no_id' => 31,
            'id3' => 3
        ];
        $my = new ArrayModificationTest($data);

        $this->assertArrayHasKey('no_id', $data);
        $my->keysLeave('id');

        $data = $my->get();
        $this->assertArrayNotHasKey('no_id', $data);
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals(1, count($data));


        $data = [
            'id' => 13,
            'no_id' => 31,
            'id3' => 3
        ];

        $my = new ArrayModificationTest($data);

        $my->keysLeave(['id', 'id3']);

        $data = $my->get();
        $this->assertEquals(2, count($data));


        $data = [
            'html1' => '<p>a</p>',
            'html2' => '<p>b</p>',
        ];

        $my = new ArrayModificationTest($data);
        $my->addFilterCallback(function($value, $key){
            return strip_tags($value);
        });
        $data = $my->filter()->get();
        $this->assertEquals('a', $data['html1']);
        $this->assertEquals('b', $data['html2']);

        $data = [
            'html1' => '<p>a</p>',
            'html2' => '<p>b</p>',
        ];

        $my = new ArrayModificationTest($data);
        $my->addFilterCallback(function($value, $key){
            return strip_tags($value);
        }, 'html1');
        $data = $my->filter()->get();
        $this->assertEquals('a', $data['html1']);
        $this->assertEquals('<p>b</p>', $data['html2']);


    }

}
