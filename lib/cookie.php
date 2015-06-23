<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 30.01.2015
 * ----------------------------------------------------
 *
 * Интерфейс для использования куков на сате.
 * Зарегистрирован в системе как сервис cookie
 *
 */


namespace Rzn\Library;

use ArrayAccess;

use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\ServiceManager\InvokeInterface;

class Cookie implements ServiceLocatorAwareInterface, ArrayAccess, InvokeInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * Время смерти куки
     * @var
     */
    protected $expire;

    /**
     * Путь для куки
     * @var string
     */
    protected $path = '/';

    protected $domain;


    public function __construct()
    {
    }

    /**
     * При вызове сервиса (ПЕРВОМ И ПОСЛЕДУЮЩИХ) запускается этот метод.
     *
     * @param ServiceManager $serviceLocator
     * @return mixed
     */
    public function invoke($serviceLocator)
    {
        // Это время используется часто в битриксе
        $this->expire = time()+60*60*24*30;
        // На субдомены распространяется
        $this->domain = '.' . Registry::get('HTTP_HOST_BASE');
    }

    public function setExpire($seconds = 0)
    {
        if ($seconds) {
            $this->expire = time() + $seconds;
        } else {
            // По окончании сессии
            $this->expire = null;
        }
        return $this;
    }

    /**
     * offsetExists(): defined by ArrayAccess interface.
     *
     * @see    ArrayAccess::offsetExists()
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * offsetGet(): defined by ArrayAccess interface.
     *
     * @see    ArrayAccess::offsetGet()
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * offsetSet(): defined by ArrayAccess interface.
     *
     * @see    ArrayAccess::offsetSet()
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        //setcookie($offset, $value, $this->expire, $this->path, $this->domain);
        $this->__set($offset, $value);
    }

    /**
     * offsetUnset(): defined by ArrayAccess interface.
     *
     * @see    ArrayAccess::offsetUnset()
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->__unset($offset);
    }


    /**
     * Magic function so that $obj->value will work.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($_COOKIE[$name])) {
            return null;
        }
        return $_COOKIE[$name];
    }

    /**
     * Set a value in the config.
     *
     * Only allow setting of a property if $allowModifications  was set to true
     * on construction. Otherwise, throw an exception.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     * @throws Exception
     */
    public function __set($name, $value)
    {
        setcookie($name, $value, $this->expire, $this->path, $this->domain);
    }

    /**
     * isset() overloading
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * unset() overloading
     *
     * @param  string $name
     * @return void
     * @throws Exception
     */
    public function __unset($name)
    {
        setcookie($name, '', time() - 3600, $this->path, $this->domain);
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