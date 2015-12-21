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

        // Проверки передачи параметров в дроп
        $user = Registry::getUser();
        $this->assertTrue($user == $result['user'], 'Не произошло инъекции');
        $this->assertTrue(['v1', 'v2'] == $result['two_params'], 'Несколько параметров передаются в метод как отдельные параметры');
        $this->assertTrue(['vv1', 'vv2'] == $result['one_param'], 'Массив передается как один параметр.');


        // Провека разделяемых данных
        $result = $waterfall->getWaterfall('auto_test', [
            'result_shared' => true
        ])->execute();
        $this->assertTrue(true === $result['y'], 'Этот параметр должен сохраниться.');

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
        $this->assertTrue('share' === $result['share'], 'К разделяемым параметрам можно получить доступ как к обычным');
        $this->assertFalse('current' === $result['share'], 'Разделяемые параметры имеют приоритет перед одноименными обычными параметрами');
        $this->assertTrue('do nothing' === $result['do nothing'], 'Второй дроп был перегружен в конфигурации');

        // Проверка передачи параметра по умолчанию в дроп и проверка работы разделяемых параметров
        $result = $waterfall->getWaterfall('auto_test', [
            'drops' => [
                'false' => ['invokable' => 'Rzn\Library\Test\Waterfall\DoNothing'],
            ]
        ])->execute();
        $this->assertTrue('current' === $result['share'], 'Нет разделяемого параметра - должны получать значение обычного');

        // Проверка работы пропуска дропа при перегрузке параметров
        $result = $waterfall->getWaterfall('auto_test', [
            'drops' => [
                'main' => ['skip' => true],
                'false' => ['skip' => true],
            ]
        ])->execute();
        $this->assertTrue('default' === $result['input'], 'Должен быть параметр по умолчанию');
        $this->assertTrue(true === $result['x'], 'Был установлен в дропе true');
        $this->assertTrue(true === $result['y'], 'Второй дроп был пропущен - параметр остался не сброшенным');

        $result = $waterfall->getWaterfall('auto_test', [
            'drops' => [
                'main' => ['skip' => true],
                'false' => ['skip' => true],
            ]
        ])->execute(['input' => 'current']);
        $this->assertTrue('current' === $result['input'], 'Параметр по-умолчанию перегружен');

        // Проверка callable перегрузку
        $result = $waterfall->getWaterfall('auto_test', [
            'drops' => [
                'false' => ['callable' => function($params, $result) {
                    $result['callable'] = 'yes';
                }],
            ]
        ])->execute();
        $this->assertTrue(null === $result['x'], 'Оригинального дропа false больше нет');
        $this->assertTrue('yes' === $result['callable'], 'Был установлен в дропе false');

        // Разделяемые данные имеют приоритет перед обычными
        $result = $waterfall->getWaterfall('auto_test', [
            'drops' => [
                'true' => ['callable' => function($params, $result) {
                    $result->addSharedResult('callable', 'shared');
                }],

                'false' => ['callable' => function($params, $result) {
                    $result['callable'] = 'yes';
                }],
            ]
        ])->execute();
        $this->assertTrue('shared' === $result['callable'], 'Разделяемый результат имеет больший приоритет');

        // Проверка работы маршрутов
        $result = $waterfall->execute('auto_test');
        $this->assertTrue(true === $result['drop_true'], 'Пропущен дроп водопада true');

        $result = $waterfall->execute('auto_test', ['route' => 'no_true']);
        $this->assertTrue(null === $result['drop_true'], 'Дроп водопада true не пропущен');
        $this->assertTrue(true === $result['drop_false'], '');
    }
}