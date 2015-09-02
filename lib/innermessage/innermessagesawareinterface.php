<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 02.09.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\InnerMessage;


interface InnerMessagesAwareInterface
{
    /**
     * Внедрение сервиса внутренних сообщений
     *
     * @param \Rzn\Library\InnerMessage\Manager $service
     * @return mixed
     */
    public function setInnerManager($service);

    /**
     * Получить сервис внутренних сообщений
     *
     * @return \Rzn\Library\InnerMessage\Manager
     */
    public function getInnerManager();

}