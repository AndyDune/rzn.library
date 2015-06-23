<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.06.2015                                      
  * ----------------------------------------------------
  *
  * Наградить классы, объекты которых могут использовать медиатор.
  * Запус действий.
  *
  */


namespace Rzn\Library\Mediator;


interface MediatorAwareInterface
{
    /**
     * Инъекция медиатора.
     * @return mixed
     */
    /**
     * @param \Rzn\Library\Mediator\Mediator $service
     */
    public function setMediator($service);

    /**
     * @return \Rzn\Library\Mediator\Mediator
     */
    public function getMediator();
}