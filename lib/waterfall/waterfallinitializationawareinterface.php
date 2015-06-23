<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 20.06.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Waterfall;


interface WaterfallInitializationAwareInterface
{
    /**
     * @param string $type drop|error|final
     * @return mixed
     */
    public function getFunctionForWaterfall($type = 'drop');
}

