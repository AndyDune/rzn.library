<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.12.13
 * Time: 12:17
 */

namespace Rzn\Library\ServiceManager;


interface BitrixApplicationInterface {

    /**
     * @param \CMain $application
     * @return mixed
     */
    public function setApplication($application);

    /**
     * @return \CMain
     */
    public function getApplication();
} 