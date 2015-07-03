<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 25.06.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Injector\Handler\SetterHandler;
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;


class Config implements ConfigServiceAwareInterface
{
    /**
     * @var \Rzn\Library\Config
     */
    protected $configService;

    public function execute($object, $params)
    {
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'set' . ucfirst($params['set']);
        }
        call_user_func([$object, $method], $this->getConfigService()->getNested($params['config']));
    }

    /**
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->configService;
    }

    public function setConfigService($config)
    {
        $this->configService = $config;
    }

}