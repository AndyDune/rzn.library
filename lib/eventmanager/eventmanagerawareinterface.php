<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 27.11.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\EventManager;
use Zend\EventManager\EventManagerInterface;

interface EventManagerAwareInterface
{
    /**
     * Внедряет менеджер событий, описываемых в конфиге.
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setConfigurableEventManager(EventManagerInterface $eventManager);

} 