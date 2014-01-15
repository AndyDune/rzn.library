<?php
/**
 * Created by Dune.
 * User: User
 * Date: 18.12.13
 * Time: 12:11
 *
 * Инициализирует установку объекта Db из битрикса.
 */

namespace Rzn\Library\ServiceManager;


interface BitrixDbInterface {

    /**
     * @param \CDatabase $db
     * @return mixed
     */
    public function setDb($db);


    /**
     * \CDatabase
     * @return mixed
     */
    public function getDb();
} 