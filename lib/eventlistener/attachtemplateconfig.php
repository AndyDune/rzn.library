<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 23.12.14                                      
  * ----------------------------------------------------
  *
  * Запуск слияния конфига из шаблона с основным конфигом.
  * Запускется событием main OnBeforeProlog после определения шаблона.
  */


namespace Rzn\Library\EventListener;

use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;

class AttachTemplateConfig implements ConfigServiceAwareInterface
{
    /**
     * @var \Rzn\Library\Config
     */
    protected $config;

    /**
     * @param $e \Rzn\Library\EventManager\Event
     */
    public function __invoke($e)
    {
        $this->getConfigService()->addTemplate();
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
    }

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->config;
    }
}