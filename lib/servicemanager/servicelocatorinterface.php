<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
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