<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.05.2015                                      
  * ----------------------------------------------------
  *
  * Класс сервиса для хранения и одноразового запуска задач на конец запроса.
  * Сам сервис носит имя completion_tasks - это если нужно использовать сервис в компонентах.
  *
  * Для внедрениея этого сервиса в другие сервисы и слушатели событий использовать интерфейс:
  * Rzn\Library\ServiceManager\CompletionTasksAwareInterface
  *
  * Для работы механизмуса отложенных задач используются:
  *
  * Rzn\Library\ServiceManager\CompletionTasksAwareInterface - интерфейс для внедрения этого сервиса
  * Rzn\Library\EventListener\ExecuteCompletionTasks - слушатель событий битрикса main -> OnLocalRedirect и OnAfterEpilog
  * Rzn\Library\ServiceManager\Initializer\CompletionTasks - инициилизатор для внедрения сервиса задач
  *
  *
  */


namespace Rzn\Library;


class CompletionTasks 
{
    /**
     * Список задачь для выполнения.
     * @var array
     */
    protected $tasks = [];

    /**
     * Добавить задачу.
     *
     * @param callable $callback Замыкание или объект и интерфейсом __invoke
     * @throws Exception
     */
    public function add($callback)
    {
        if (!is_callable($callback)) {
            throw new Exception('Метод ждет в качестве параметра замыкание.');
        }
        $this->tasks[] = $callback;
        return $this;
    }

    /**
     * Выполнить все зарегистрированные задачи.
     * Происходит очищение списка задач.
     */
    public function execute()
    {
        foreach($this->tasks as $task) {
            call_user_func($task);
        }
        $this->tasks = [];
    }
}