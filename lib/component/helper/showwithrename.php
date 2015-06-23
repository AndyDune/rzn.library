<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 25.05.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;


class ShowWithRename  extends HelperAbstract
{
    public function __invoke($value, $variants)
    {
        if (array_key_exists($value, $variants)) {
            return $variants[$value];
        }
        return $value;
    }
}