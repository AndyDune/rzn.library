<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 15.12.2015                                     |
 * ----------------------------------------------------
 *
 */


namespace Rzn\Library\Test\Waterfall;


class DoNothing
{
    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        $result['do nothing'] = 'do nothing';
        $result['share']     = 'current';
    }

}