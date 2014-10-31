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

use Rzn\Library\ServiceManager\FactoryInterface;

class HelperManagerFactory implements FactoryInterface
{
    public function createService($serviceLocator, $name = null)
    {
        $object = new HelperManager();
        $object->setInvokableClass('url', 'Rzn\Library\Component\Helper\Url');
        $object->setInvokableClass('isAjax', 'Rzn\Library\Component\Helper\IsAjax');
        $object->setInvokableClass('getFileArray', 'Rzn\Library\Component\Helper\GetFileArray');
        $object->setInvokableClass('printOuterLink', 'Rzn\Library\Component\Helper\printOuterLink');
        $object->setInvokableClass('truncateHtml', 'Rzn\Library\Component\Helper\TruncateHtml');
        return $object;

    }

}