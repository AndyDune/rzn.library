<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 24.08.2015                                      
  * ----------------------------------------------------
  *
  * Абстрактная фабрика для менеджеров сервисов.
  * В конфиге можно описывать свои собственные менеджеры и их сервисы.
  *
  *  Пользовательские менеджеры описываются в конфиге так:
  *
        'custom_service_managers' => [
            '<имя менеджера сервисов>' => [
                'invokables' => [
                ],
                'factories' => [
                ],
                'aliases' => [

                ]
            ]
        ],
  * Пример использования:
  * $model = $sm->get('custom_service_managers')->get('models')->get('messages');
*/

namespace Rzn\Library\ServiceManager;
use Rzn\Library\Exception;

class AbstractFactory implements ConfigServiceAwareInterface
{
    protected $config;

    protected $servicesConfig = [];

    protected $serviceManagers = [];


    public function get($serviceManagerName)
    {
        if (isset($this->serviceManagers[$serviceManagerName])) {
            return $this->serviceManagers[$serviceManagerName];
        }

        if (!isset($this->servicesConfig[$serviceManagerName])) {
            throw new Exception('Менеджера сервисов не существует: ' . $serviceManagerName, 100);
        }
        $object = new ServiceManager();
        $object->setConfig($this->servicesConfig[$serviceManagerName]);
        $this->serviceManagers[$serviceManagerName] = $object;
        return $object;

    }

    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {
        $this->config = $service;
        if (isset($service['custom_service_managers'])) {
            $this->servicesConfig = $service['custom_service_managers'];
        }
    }

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->configService;
    }

}