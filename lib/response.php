<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 *
 * Пример использования в футере:
    $APPLICATION = Registry::getApplication();

    $sm = Registry::getServiceManager();
    $response = $sm->get('response');

    $response->setMainContent($APPLICATION->EndBufferContent());
    $APPLICATION->RestartBuffer();

    if (isAjax()) {
        echo $response;
        return;
    }

 *
 */

namespace Rzn\Library;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\ServiceManager\BitrixUserInterface;

class Response implements \Iterator, \ArrayAccess, \Countable, ServiceLocatorAwareInterface, BitrixUserInterface
{
    protected $mainContentBuffer = false;

    protected $mainContent = '';

    protected $isJson = null;

    protected $layout = 'default';

    protected $subLayoutName   = '';
    protected $subLayoutParams = [];

    protected $array = [];


    protected $serviceManager = null;

    /**
     * @var \CUser
     */
    protected $user = null;


    /**
     * @param \CUser $user
     * @return mixed
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \CUser
     */
    public function getUser()
    {
        return $this->user;
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

    /**
     * Используется в футере (footer.php) для сохранения основного содержимого.
     * Сохраненное используется для вставки с генеральный шаблон или отпарвки прямо в браузеру.
     *
     *
     * @param $text
     * @return $this
     */
    public function setMainContent($text)
    {
        $this->mainContent = $text;
        return $this;
    }

    public function useJson($flag = true)
    {
        $this->isJson = $flag;
        return $this;
    }

    public function setLayout($name = '')
    {
        $this->layout = $name;
        return $this;
    }

    /**
     * Установка сублейаута. Общего для нескольких страниц участка в разметке в рамках генерального лейаута.
     *
     * @param $name имя подключаемого файла
     * @param array $params параметры для передачи в лейаут
     * @return $this
     */
    public function setSubLayout($name, $params = [])
    {
        $this->subLayoutName   = $name;
        $this->subLayoutParams = $params;
        return $this;
    }

    public function getSubLayout()
    {

        return $this->subLayoutName;
    }

    /**
     * Выбрать параметры для передачи в шаблон сублейаута.
     *
     * @param null $key
     * @param string $default
     * @return array|string
     */
    public function getSubLayoutParam($key = null, $default = '')
    {
        if (!$key) {
            return $this->subLayoutParams;
        }
        if (isset($this->subLayoutParams[$key])) {
            return $this->subLayoutParams[$key];
        }
        return $default;
    }


    /**
     * Уставновка свойства для передачи в шаблон сублейацта.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setSubLayoutParam($key, $value)
    {
        $this->subLayoutParams[$key] = $value;
        return $this;
    }


    /**
     * Возможность запускать хелперы шаблонов из сублейаутов.
     *
     * @param $function
     * @param $params
     * @return mixed
     */
    public function __call($function, $params)
    {
        $helper = Registry::getServiceManager()->get('helper_manager');
        return call_user_func_array([$helper, $function], $params);
    }

    /**
     * Подключать сублейаут.
     *
     * @param $rootDir
     * @return $this
     */
    public function includeSubLayout($rootDir)
    {
        ob_start();
        include($rootDir . '/' . $this->getSubLayout() . '.php');
        $this->setMainContent(ob_get_clean());
        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Включено ли ранее использования ответа json.
     * Включено, если добавлены данные при отсутствии явного запрета за json.
     *
     * @return bool
     */
    public function isJson()
    {
        if (count($this->array) and $this->isJson === null) {
            return true;
        }
        return $this->isJson;
    }


    public function __toString()
    {
        if ($this->isJson()) {
            $this->array['html'] = $this->mainContent;
            return json_encode($this->array);
        }
        return $this->mainContent;
    }


    /**
     * Поддержка isset() перегружено в PHP 5.1
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->array[$name]);
    }

    /**
     * Поддержка unset() перегружено в PHP 5.1
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        unset($this->array[$name]);
    }

////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса Iterator
    // устанавливает итеретор на первый элемент
    public function rewind()
    {
        return reset($this->array);
    }
    // возвращает текущий элемент
    public function current()
    {
        return current($this->array);
    }
    // возвращает ключ текущего элемента
    public function key()
    {
        return key($this->array);
    }

    // переходит к следующему элементу
    public function next()
    {
        return next($this->array);
    }
    // проверяет, существует ли текущий элемент после выполнения мотода rewind или next
    public function valid()
    {
        return isset($this->array[key($this->array)]);
    }
/////////////////////////////
////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса ArrayAccess
    /**
     * @param mixed $key
     * @return mixed
     * @access private
     */
    public function offsetExists($key)
    {
        return isset($this->array[$key]);
    }
    public function offsetGet($key)
    {
        if (isset($this->array[$key]))
            return $this->array[$key];
        else
            return null;
    }

    public function offsetSet($key, $value)
    {
        $this->array[$key] = $value;
    }
    public function offsetUnset($key)
    {
        unset($this->array[$key]);
    }


////////////////////////////////////////////////////////////////
///////////////////////////////     Методы интерфейса Countable
    public function count()
    {
        return count($this->array);
    }

}