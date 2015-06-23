<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.05.2015                                      
  * ----------------------------------------------------
  *
  * Этим интрефейсом надо наделять сервисы и слушатели событий для использонваия в них задач на конец скрипта.
  */


namespace Rzn\Library\ServiceManager;


interface CompletionTasksAwareInterface
{
    /**
     * Внедрение сервиса задач на завершение
     *
     * @param $service
     * @return mixed
     */
    public function setCompletionTasksService($service);

    /**
     * выборка севрса задач на хавершение.
     * @return \Rzn\Library\CompletionTasks
     */
    public function getCompletionTasksService();
}