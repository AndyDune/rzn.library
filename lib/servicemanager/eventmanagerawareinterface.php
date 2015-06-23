<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 15.04.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\ServiceManager;


interface EventManagerAwareInterface
{
    /**
     * Инъекция менеджера событий.
     *
     * @param \Rzn\Library\EventManager\EventManager $service
     * @return mixed
     */
    public function setEventManager($service);

    /**
     * Возврат менеджера событий.
     *
     * @return \Rzn\Library\EventManager\EventManager
     */
    public function getEventManager();

}