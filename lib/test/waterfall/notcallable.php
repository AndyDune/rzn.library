<?php

/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 14.12.2015                                  |
 * ----------------------------------------------------
 *
 * Создан исключительно для тестирования влодопада.
 * Тест возникновения ошибки при отсутствии метода __invoke
 *
 */
namespace Rzn\Library\Test\Waterfall;
class NotCallable
{
    public function __invokeNot()
    {

    }
}