<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 18.08.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Injector;


interface OptionsRetrieverInterface
{
    /**
     * Выбрать отции для инъектора.
     * @return array
     */
    public function getInjectorOptions();
}