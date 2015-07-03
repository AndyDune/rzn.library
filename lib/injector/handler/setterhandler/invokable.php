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
use Rzn\Library\Injector\InjectorAwareInterface;

class Invokable implements InjectorAwareInterface
{
    /**
     * @var \Rzn\Library\Injector\Injector
     */
    protected $injector;


    public function execute($object, $params)
    {
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'set' . ucfirst($params['set']);
        }
        $class = $params['class'];
        $newObject= new $class();
        if (isset($params['injector'])) {
            $this->getInjector()->inject($newObject, $params['injector']);
        }
        call_user_func([$object, $method], $newObject);
    }

    /**
     * @return \Rzn\Library\Injector\Injector
     */
    public function getInjector()
    {
        return $this->injector;
    }

    /**
     * Внедрение водопала делается инъектором
     * @param $injector
     */
    public function setInjector($injector)
    {
        $this->injector = $injector;
    }

}