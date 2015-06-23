<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 *
 * Отложенная инициилизация для сервисов и хелперов.
 * Этот механизмус полностью заменен инициилизаторами.
 */

namespace Rzn\Library\ServiceManager;

/**
 * @deprecated
 * Class LazyConfig
 * @package Rzn\Library\ServiceManager
 */
class LazyConfig implements InitializerInterface
{
    /**
     * @var ServiceManager
     */
    protected $sm;

    protected $setters = array();

    /**
     * @param ServiceManager $sm
     */
    public function __construct(ServiceManager $sm)
    {
        $this->sm = $sm;
    }


    /**
     * Введена возможность
     *
     * @param $class Имя класса или имя зарегистрированного сериса.
     * @param $method
     * @param $params
     * @return $this
     */
    public function addSetter($class, $method, $params)
    {
        if (!array_key_exists($class, $this->setters)) {
            $this->setters[$class] = array();
        }
        $this->setters[$class][] = array('method' => $method, 'params' => $params);
        return $this;
    }

    public function initialize($object, $name)
    {
        if (!is_object($object))
            return false;

        // Ключ для поздней загрузки может быть не только имя класса, но и имя сервиса.
        // Не алиаса!
        if ($name and array_key_exists($name, $this->setters)) {
            $class = $name;
        } else {
            $class = get_class($object);
            if (!array_key_exists($class, $this->setters))
                return true;
        }
        foreach($this->setters[$class] as $setter) {
            $method = $setter['method'];
            $params = $setter['params'];
            if (!is_array($params))
                $params = array($params);
            call_user_func_array(array($object, $method), $params);
        }
    }


} 