<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */

namespace Rzn\Library\ServiceManager;


interface BitrixUserInterface
{
    /**
     * @param \CUser $user
     * @return mixed
     */
    public function setUser($user);

    /**
     * @return \CUser
     */
    public function getUser();

} 