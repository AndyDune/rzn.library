<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 15.01.15                                      
  * ----------------------------------------------------
  *
  * Объект этого класса не внесен в сервисы - рассмотреть необходимость этого.
  *
  */


namespace Rzn\Library\Service;
use Rzn\Library\Registry;


class ApplicationContainer 
{
    /**
     * @var \CMain
     */
    protected  $application;

    /**
     * @var \Rzn\Library\EventManager\EventManager
     */
    protected  $eventManager;

    /**
     * @param \CMain $application
     */
    public function __construct($application)
    {
        $this->application = $application;
        $this->eventManager = Registry::getServiceManager()->get('event_manager');
    }

    public function __call($name, $arguments)
    {
        // Отслеживаем каждый запуск подключения компонента.
        if ($name == 'IncludeComponent') {
            /** @var \Zend\EventManager\ResponseCollection $res */
            $arguments = $this->eventManager->prepareArgs($arguments);
            $res = $this->eventManager->trigger('call.include.component', $this->application, $arguments);
            if ($res->stopped()) {
                return null;
            }
            $arguments = $arguments->getArrayCopy();
        }
        return call_user_func_array([$this->application, $name], $arguments);
    }

    public function __get($name)
    {
        return $this->application->{$name};
    }

    public function __set($name, $value)
    {
        $this->application->{$name} = $value;
    }

} 