<?php

/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 14.12.2015                                     |
 * ----------------------------------------------------
 *
 */
namespace Rzn\Library\Tests;
use Rzn\Library\Registry;
use PHPUnit_Framework_TestCase;

class WaterfallTest extends PHPUnit_Framework_TestCase
{
    public function testConfigDescriptionErrors()
    {
        $sm = Registry::getServiceManager();

        /** @var \Rzn\Library\Waterfall\Check $waterfallCheck */
        $waterfallCheck = $sm->get('waterfall_check');
        $checkDetails = $waterfallCheck->checkStream([
            'drops' => [
                'test' => 'Rzn\Library\Test\Waterfall\ThisDropNoExist'
            ]
        ]);
        //pr($checkDetails);
        $errors = $waterfallCheck->getErrors();
        $this->assertEquals(1, count($errors), 'Должна быть реакция на несуществующий ключ в описании дропа');


        $checkDetails = $waterfallCheck->checkStream([
            'drops' => [
                'test' => ['invokable' => 'Rzn\Library\Test\Waterfall\ThisDropNoExist']
            ]
        ]);
        //pr($checkDetails);
        $errors = $waterfallCheck->getErrors();
        $this->assertEquals(1, count($errors), 'Должна быть реакция на несуществующий класс дропа');

        $checkDetails = $waterfallCheck->checkStream([
            'drops' => [
                'test' => ['invokable' => 'Rzn\Library\Test\Waterfall\NotCallable']
            ]
        ]);
        //pr($checkDetails);
        $errors = $waterfallCheck->getErrors();
        $this->assertEquals(1, count($errors), 'Должна быть реакция на несуществующий интерфейс __invoke()');

        $checkDetails = $waterfallCheck->checkStream([
            'drops' => [
                'test' => ['invokable' => 'Rzn\Library\Test\Waterfall\InvokeDoNotHaveParams']
            ]
        ]);
        //pr($checkDetails);
        $errors = $waterfallCheck->getErrors();
        $this->assertEquals(1, count($errors), 'Должна быть реакция на неверное количество параметров метода __invoke');


    }
}