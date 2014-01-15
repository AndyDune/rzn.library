<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.12.13
 * Time: 17:49
 */

namespace Rzn\Library\ServiceManager;


interface FactoryInterface
{
    /**
     * Создает сервис.
     *
     * @param \Rzn\Library\ServiceManager $serviceLocator
     * @return mixed
     */
    public function createService($serviceLocator);
}