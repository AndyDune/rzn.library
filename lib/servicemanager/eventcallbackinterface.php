<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 22.09.14                                      
  * ----------------------------------------------------
  *
  * Награждаем сервис локальными событиями.
  * Рекомендуется использовать сервис в паре с фабрикой для регистрации обработчиков.
  *
  */


namespace Rzn\Library\ServiceManager;


interface EventCallbackInterface
{
    /**
     * Регистрация слушателя события.
     *
     * Пример использования в классе-фабрике:
     *
    public function createService($serviceLocator, $name = null)
    {
        $object = new ParticipationOrder(Registry::get('iblock_shop_order_id'));
        $object->setShop($serviceLocator->get('shop_data'));
        $object->addListenerCallback('after.order.payed', function($params) use ($object, $serviceLocator){
                $shopEditObject = $serviceLocator->get('shop_edit');
                // Делаем необходимое
                // ...
            });
        return $object;
    }

     *
     * Рекомендуемый пример реализации:
     *
    public function addListenerCallback($eventLocalName, $callback)
    {
        if (!array_key_exists($eventLocalName, $this->_eventListeners)) {
            $this->_eventListeners[$eventLocalName] = [];
        }
        $this->_eventListeners[$eventLocalName][] = $callback;
        return $this;
    }

     *
     * @param $eventLocalName имя события
     * @param $callback вызываемый объекст (замыкание или объект ч интерфейсом __invoke)
     * @return mixed
     */
    public function addListenerCallback($eventLocalName, $callback);


    /**
     * Метод, который запускет привязанные обработчики.
     *
     * Рекомендуемый вариант реализации:
     *
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

     *
     * @param $eventLocalName
     * @param null|array $params параметры для передачи в обработчик
     * @return mixed
     */
    public function triggerCallback($eventLocalName, $params = null);

} 