<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 *
 * Подключение файла с компонентом в рамках текущего шаблона.
 * Обернул операция в целях передачи параметров
 *
 */


namespace Rzn\Library\Component;
use Rzn\Library\ServiceManager\InvokeInterface;


class IncludeWithTemplate implements InvokeInterface
{
    protected $params = [];

    protected $successIncludeFlag = false;
    protected $freeIncludeFlag = false;

    /**
     * Запускается сразу же после каждого вызова из менеджера.
     *
     * @param $serviceManager
     * @return mixed|void
     */
    public function invoke($serviceManager)
    {
        $this->successIncludeFlag = false;
        $this->freeIncludeFlag    = false;
    }

    public function __invoke($name, $params = [])
    {
        $this->params = $params;

        $path = $_SERVER['DOCUMENT_ROOT'] . '/local/templates/' . SITE_TEMPLATE_ID . '/include/component/' . $name . '.php';
        if (is_file($path)) {
            $this->successIncludeFlag = true;
            return $this->_include($path);
        }
        return null;
    }

    /**
     * Подключался ли файл.
     * Не подключается в основном из-за отсутствия файла в нужном месте или из-за запрета запуска его напрямую.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->successIncludeFlag;
    }

    /**
     *
     * @param $path
     * @return mixed
     */
    protected function _include($path)
    {
        return include($path);
    }

    public function setFreeInclude($flag = true)
    {
        $this->freeIncludeFlag = $flag;
        return $this;
    }


    /**
     * Запускать метод для закрытия запуска подключения компонента при свободном запуске.
     * Своюдный зыпуск: из скрипта в корне component.php
     *
     * @return bool
     */
    protected function banFreeInclude()
    {
        if ($this->freeIncludeFlag) {
            $this->successIncludeFlag = false;
            return true; // Отмена выполнения
        }
        return false; // все в порядке
    }


    protected function __get($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }
} 