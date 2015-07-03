<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 03.07.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Injector;


interface InjectorAwareInterface
{
    /**
     * @return \Rzn\Library\Injector\Injector
     */
    public function getInjector();

    /**
     * Внедрение водопала делается инъектором
     * @param $injector
     */
    public function setInjector($injector);
}