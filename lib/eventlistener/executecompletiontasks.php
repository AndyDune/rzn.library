<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.05.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\EventListener;
use Rzn\Library\ServiceManager\CompletionTasksAwareInterface;

class ExecuteCompletionTasks implements CompletionTasksAwareInterface
{
    protected $completionTasksService;



    public function __invoke($e)
    {
        $tasks = $this->getCompletionTasksService();
        $tasks->execute();
    }

    /**
     * Внедрение сервиса задач на завершение
     *
     * @param $service
     * @return mixed
     */
    public function setCompletionTasksService($service)
    {
        $this->completionTasksService = $service;
    }


    /**
     * выборка севрса задач на хавершение.
     * @return \Rzn\Library\CompletionTasks
     */
    public function getCompletionTasksService()
    {
        return $this->completionTasksService;
    }

}