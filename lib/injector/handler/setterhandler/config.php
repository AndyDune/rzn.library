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
     * Проверка
     *
     * @param $object
     * @param $params
     * @return array
     */
    public function check($object, $params)
    {
        $errors = [];
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'set' . ucfirst($params['set']);
        }
        if (!method_exists($object, $method)) {
            $errors[] = 'Субъект инъекции (' . get_class($object) . ') не имеет целевого метода: ' . $method;
        }
        if (!isset($params['config'])) {
            $errors[] = 'В инструкции инъектора не задан важный парамтер config';
        }
        if ($this->getConfigService()->getNested($params['config']) === null) {
            $errors[] = 'Конфигурация ' . $params['config'] . ' возвращает NULL';
        }
        return $errors;
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