<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 13.08.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Waterfall\Test;


class DropStop
{
    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        pr('Дроп для проверки тестовой остановки внутри функции:' . $result->getCurrentFunction());
        $result->stop('Остановили водопад внутри дропа');
    }

}