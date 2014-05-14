<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
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