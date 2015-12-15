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


        $checkDetails = $waterfallCheck->checkStream('auto_test', [
            'drops' => [
                'false' => ['invokable' => 'Rzn\Library\Test\Waterfall\DoNothing'],
            ]
        ]);
        $errors = $waterfallCheck->getErrors();
        //pr($checkDetails);
        $this->assertEquals(0, count($errors), 'Должна быть сделана правильная перегрузка дропа');


    }

    public function testWork()
    {
        $sm = Registry::getServiceManager();

        /** @var \Rzn\Library\Waterfall\WaterfallCollection $waterfall */
        $waterfall = $sm->get('waterfall');
        $result = $waterfall->execute('auto_test');
        $this->assertTrue(false === $result['x']);
        $this->assertTrue(null === $result['y'], 'Параметры в этой конфигурации не должны разделяться между дропами');

        // Проверка передачи параметра по умолчанию в дроп и проверка работы разделяемых параметров
        $result = $waterfall->getWaterfall('auto_test', [
            'drops' => [
                'true'  => ['params' => ['callback' => function($result) {
                    $result->addSharedResult('share', 'share');
                }]],
                'false' => ['invokable' => 'Rzn\Library\Test\Waterfall\DoNothing'],
            ]
        ])->execute();
        $this->assertTrue('share' === $result->getSharedResult('share'));
        $this->assertTrue('do nothing' === $result['do nothing'], 'Второй дроп был перегружен в конфигурации');

        // Проверка работы пропуска дропа при перегрузке параметров
        $result = $waterfall->getWaterfall('auto_test', [
            'drops' => [
                'false' => ['skip' => true],
            ]
        ])->execute();
        $this->assertTrue(true === $result['x'], 'Был установлен в дропе true');
        $this->assertTrue(true === $result['y'], 'Второй дроп был пропущен - параметр остался не сброшенным');

    }
}