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


class DropLast 
{
    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        pr('Последний дроп в серии:' . $result->getCurrentFunction());
        pr($result->getSharedResults());
    }

}