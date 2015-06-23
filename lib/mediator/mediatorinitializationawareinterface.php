<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.06.2015                                      
  * ----------------------------------------------------
  *
  * Наградить классы, объекты которых могут добавлять действия в медиатор.
  */


namespace Rzn\Library\Mediator;


interface MediatorInitializationAwareInterface
{
    public function getFunctionForMediatorSubscribe($channel);
}