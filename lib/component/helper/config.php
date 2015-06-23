<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 21.11.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

use \Rzn\Library\ServiceManager\ConfigServiceAwareInterface;

class Config extends HelperAbstract implements ConfigServiceAwareInterface
{
    /**
     * @var \Rzn\Library\Config
     */
    protected $config = null;

    public function __invoke($value, $default = null)
    {
        return $this->config->getNested($value, $default);
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
