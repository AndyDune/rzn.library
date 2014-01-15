<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.12.13
 * Time: 16:58
 */

namespace Rzn\Library\ServiceManager;


interface InvokeInterface {

    /**
     * При выхове сервиса (ПЕРВОМ И СОСЛЕДУЮЩИХ) запускается этот метод.
     *
     * @param \Rzn\Library\ServiceManager $serviceLocator
     * @return mixed
     */
    public function invoke($serviceManager);

} 