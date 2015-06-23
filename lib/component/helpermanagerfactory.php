<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 13.10.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Component;

use Rzn\Library\Registry;
use Rzn\Library\ServiceManager\FactoryInterface;

class HelperManagerFactory implements FactoryInterface
{
    /**
     * @param \Rzn\Library\ServiceManager\ServiceManager $serviceLocator
     * @param null $name
     * @return mixed|HelperManager
     */
    public function createService($serviceLocator, $name = null)
    {
        $object = new HelperManager();
        $config = $serviceLocator->get('config');
        if (isset($config['view_helpers'])) {
            $object->setConfig($config['view_helpers']);
            $object->initServicesFromConfig($serviceLocator);
        }
        return $object;
    }

}