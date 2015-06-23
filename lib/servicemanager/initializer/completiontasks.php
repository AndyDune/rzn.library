<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.05.2015                                      
  * ----------------------------------------------------
  *
  * Наделяет объекты сервисов и слушателей событий сервисом задач на конец запроса.
  */


namespace Rzn\Library\ServiceManager\Initializer;

use Rzn\Library\ServiceManager\InitializerInterface;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\CompletionTasksAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;


class CompletionTasks implements InitializerInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;


    /**
     * Метод запускается для каждого вновь создаваемого сервиса.
     *
     * @param $instance
     * @param $serviceManager
     */
    public function initialize($instance, $serviceManager)
    {
        /**
         * Если класс объекта реализует интерфейс CompletionTasksAwareInterface делаем инъекцию сервисом конфигов
         */
        if ($instance instanceof CompletionTasksAwareInterface) {
            $instance->setCompletionTasksService($this->getServiceLocator()->get('completion_tasks'));
        }
    }

    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceManager;
    }

}