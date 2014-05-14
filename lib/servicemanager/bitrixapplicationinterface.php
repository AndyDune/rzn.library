<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
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