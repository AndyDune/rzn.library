<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */

namespace Rzn\Library\ServiceManager;


class LazyConfig implements InitializerInterface
{
    /**
     * @var \Rzn\Library\ServiceManager
     */
    protected $sm;

    protected $setters = array();

    /**
     * @param \Rzn\Library\ServiceManager $sm
     */
    public function __construct(\Rzn\Library\ServiceManager $sm)
    {
        $this->sm = $sm;
    }


    public function addSetter($class, $method, $params)
    {
        if (!array_key_exists($class, $this->setters)) {
            $this->setters[$class] = array();
        }
        $this->setters[$class][] = array('method' => $method, 'params' => $params);
        return $this;
    }

    public function initialize($object)
    {
        if (!is_object($object))
            return false;

        $class = get_class($object);
/*
        pr($class);
        if ($class == 'Rzn\Library\Component\HelperManager') {
            pr($this->setters);
            die();
        }
*/

        if (!array_key_exists($class, $this->setters))
            return true;
        foreach($this->setters[$class] as $setter) {
            $method = $setter['method'];
            $params = $setter['params'];
            if (!is_array($params))
                $params = array($params);
            call_user_func_array(array($object, $method), $params);
        }
    }


} 