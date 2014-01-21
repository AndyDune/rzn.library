<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20.01.14
 * Time: 12:26
 */

namespace Rzn\Library\ServiceManager;


interface ServiceLocatorInterface {
    /**
     * Возврат зарегистрированного объекта.
     *
     * @param  string  $name
     * @return object|array
     */
    public function get($name);

    /**
     * Проверка на регистрацию сущности с указанным именем.
     *
     * @param  string|array  $name
     * @return bool
     */
    public function has($name);
} 