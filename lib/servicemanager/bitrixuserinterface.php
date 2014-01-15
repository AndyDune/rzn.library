<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.12.13
 * Time: 12:15
 */

namespace Rzn\Library\ServiceManager;


interface BitrixUserInterface {
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