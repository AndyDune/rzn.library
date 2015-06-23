<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 10.02.2015
 * ----------------------------------------------------
 *
 * Интерфейс, которым надо наградить любой сервис для инъекции в него сервиса куков.
 * Инъекция делается инициилизатором Rzn\Library\ServiceManager\Initializer\CookieService
 */


namespace Rzn\Library\ServiceManager;


interface CookieServiceAwareInterface
{
    /**
     * Инъекция сервиса куков.
     *
     * @param \Rzn\Library\Cookie $service
     * @return mixed
     */
    public function setCookieService($service);

    /**
     * Возврат сервиса куков.
     *
     * @return \Rzn\Library\Cookie
     */
    public function getCookieService();
}