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


class SetParamsTrue
{
    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        if (isset($params['callback']) and is_callable($params['callback'])) {
            call_user_func($params['callback'], $result);
        }
        $result['x'] = true;
        $result['y'] = true;
    }
}