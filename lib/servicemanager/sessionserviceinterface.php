<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */


namespace Rzn\Library\ServiceManager;

interface SessionServiceInterface
{
    /**
     * @param \Rzn\Library\Session $service
     * @return mixed
     */
    public function setSessionService($service);

    /**
     * @param $service
     * @return \Rzn\Library\Session
     */
    public function getSessionService();
} 