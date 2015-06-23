<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 10.02.2015
 * ----------------------------------------------------
 *
 * Интерфейс, которым надо наградить любой сервис для инъекции в него сервиса конфигов.
 * Инъекция делается инициилизатором Rzn\Library\ServiceManager\Initializer\ConfigService
 */


namespace Rzn\Library\ServiceManager;


interface ConfigServiceAwareInterface
{
    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service);

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService();
}