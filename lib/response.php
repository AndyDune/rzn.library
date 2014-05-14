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

class Response implements \Iterator, \ArrayAccess, \Countable
{
    protected $mainContentBuffer = false;

    protected $mainContent = '';

    protected $isJson = null;

    protected $array = [];

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