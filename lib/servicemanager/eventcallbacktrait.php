<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 13.10.14                                      
  * ----------------------------------------------------
  *
  * Треит с готовым решением встраиваемых в сервис событий.
  *
  *
  */


namespace Rzn\Library\ServiceManager;


trait EventCallbackTrait
{
    protected $_eventListeners = [];

    public function addListenerCallback($eventLocalName, $callback)
    {
        if (!array_key_exists($eventLocalName, $this->_eventListeners)) {
            $this->_eventListeners[$eventLocalName] = [];
        }
        $this->_eventListeners[$eventLocalName][] = $callback;
        return $this;
    }

    public function triggerCallback($eventLocalName, $params = null)
    {
        if (array_key_exists($eventLocalName,  $this->_eventListeners)) {
            foreach($this->_eventListeners[$eventLocalName] as $callback) {
                if (!$callback($params)) {
                    return false;
                }
            }
        }
        return true;
    }

} 