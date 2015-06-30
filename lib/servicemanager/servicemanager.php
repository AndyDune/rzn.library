<?php
/**
 * Copyright (c) 2014 Andrey Ryzhov.
 * All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     rzn.library
 * @author      Andrey Ryzhov <info@rznw.ru>
 * @copyright   2014 Andrey Ryzhov.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://rznw.ru
 */

namespace Rzn\Library\ServiceManager;

//use Rzn\Library\ServiceManager\FactoryInterface;
//use Rzn\Library\ServiceManager\InvokeInterface;
//use Rzn\Library\ServiceManager\InitializerInterface;
//use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
//use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\Config;
use Rzn\Library\Exception;


class ServiceManager implements ServiceLocatorInterface, ServiceLocatorAwareInterface
{
    protected $instances = array();

    protected $factories = array();

    protected $invokableClasses = array();

    protected $serviceLocator;

    /**
     * Зафиксированные сервисы.
     * Повторное инициилизация будет проигнорирована.
     * Без этого вызывается исключение либо переинициилизация со сбросом созданного объекта.
     * @var array
     */
    protected $serviceFixed = array();

    protected $aliases = array();


    /**
     * Флаг разрешения перезаписи сервисов.
     * @var bool
     */
    protected $allowOverride = false;


    protected $shareByDefault = true;

    protected $shared = array();


    protected $throwExceptionInCreate = true;

    /**
     * @var InitializerInterface
     */
    protected $interfaceInitializer = array();


    /**
     * Сохраненные отформатированные имена.
     *
     * @var array
     */
    protected $canonicalNames = array();

    /**
     * @var array map of characters to be replaced through strtr
     */
    protected $canonicalNamesReplacements = array('-' => '', '_' => '', ' ' => '', '\\' => '', '/' => '');

    /**
     * @var Config
     */
    protected $configObject = null;

    protected $servicesInitDone = false;


    protected $initConfigMade = false;


    /**
     * @return Config
     */
    public function getConfig()
    {
        if (!$this->configObject) {
            $this->configObject = new Config([]);
        }
        return $this->configObject;
    }

    public function setConfig($config)
    {
        $this->servicesInitDone = false;
        $this->configObject = $config;
    }

    /**
     * Инициилизация сервисов из массива с конфигом.
     *
     * Сервис можно объявлять с указанием разделения:
        'element' => ['Citimall\Library\Factory\Service\Element',
            'shared' => false // Каждый раз при запрос создается новый объект
        ],
      *
     * @return $this
     */
    public function initServicesFromConfig()
    {
        //echo '<p>initServicesFromConfig</p>';
        $config = $this->getConfig();
        if (isset($config['invokables'])) {
            foreach($config['invokables'] as $key => $value) {
                $shared = null;
                if ($value instanceof Config or is_array($value)) {
                    if (isset($value['shared'])) {
                        $shared = $value['shared'];
                    }
                    if (isset($value['name'])) {
                        $value = $value['name'];
                    } else {
                        $value = $value[0];
                    }
                }
                $this->setInvokableClass($key, $value);
                // Уставновка shared возможна только после внедрения самого сервиса.
                if ($shared !== null) {
                    $this->setShared($key, $shared);
                }
            }
        }

        if (isset($config['factories'])) {
            foreach($config['factories'] as $key => $value) {
                $shared = null;
                if ($value instanceof Config or is_array($value)) {
                    if (isset($value['shared'])) {
                        $shared = $value['shared'];
                    }
                    if (isset($value['name'])) {
                        $value = $value['name'];
                    } else {
                        $value = $value[0];
                    }
                }
                $this->setFactory($key, $value);
                // Уставновка shared возможна только после внедрения самого сервиса.
                if ($shared !== null) {
                    $this->setShared($key, $shared);
                }
            }
        }

        if (isset($config['aliases'])) {
            foreach($config['aliases'] as $key => $value) {
                $this->setAlias($key, $value);
            }
        }

        if (isset($config['initializers'])) {
            foreach($config['initializers'] as $key => $value) {
                $initializer = new $value();
                if ($initializer instanceof ServiceLocatorAwareInterface) {
                    $initializer->setServiceLocator($this->getServiceLocator());
                }
                $this->addInitializer($initializer);
            }
        }
        return $this;
    }


    /**
     * Внедрение инициализатора через интерфейсы.
     * @param InterfaceInitializer $object
     * @return $this
     */
    public function setInitializer(InitializerInterface $object)
    {
        $this->interfaceInitializer[] = $object;
        return $this;
    }

    /**
     *
     * Внедрение инициализатора через интерфейсы.
     * Этот метод ппедпочтительней сипользовать.
     *
     * @param InterfaceInitializer $object
     * @return $this
     */
    public function addInitializer(InitializerInterface $object)
    {
        $this->interfaceInitializer[] = $object;
        return $this;
    }


    /**
     * Set allow override
     *
     * @param $allowOverride
     * @return ServiceManager
     */
    public function setAllowOverride($allowOverride)
    {
        $this->allowOverride = (bool) $allowOverride;
        return $this;
    }

    /**
     * Get allow override
     *
     * @return bool
     */
    public function getAllowOverride()
    {
        return $this->allowOverride;
    }

    /**
     * Retrieve a registered instance
     *
     * @param  string  $name
     * @throws Exception
     * @return object|array
     */
    public function get($name)
    {
        if (!$this->initConfigMade) {
            $this->initConfigMade = true;
            $this->initServicesFromConfig();
        }
        // inlined code from ServiceManager::canonicalizeName for performance
        if (isset($this->canonicalNames[$name])) {
            $cName = $this->canonicalNames[$name];
        } else {
            $cName = $this->canonicalizeName($name);
        }

        $isAlias = false;

        if (isset($this->aliases[$cName])) {
            $isAlias = true;

            do {
                $cName = $this->aliases[$cName];
            } while ($this->hasAlias($cName));
        }

        $instance = null;


        if (isset($this->instances[$cName])) {
            $instance = $this->instances[$cName];
            goto end;
            //return $instance;
        }

        if (!$instance) {
            if (
                isset($this->invokableClasses[$cName])
                || isset($this->factories[$cName])
                || isset($this->aliases[$cName])
                || isset($this->instances[$cName])
            ) {
                $instance = $this->create(array($cName, $name));
            }
        }

        // Still no instance? raise an exception
        if ($instance === null) {
            if ($isAlias) {
                throw new Exception(sprintf(
                    'An alias "%s" was requested but no service could be found.',
                    $name
                ));
            }

            throw new Exception(sprintf(
                '%s was unable to fetch or create an instance for %s',
                get_class($this) . '::' . __FUNCTION__,
                $name
            ));
        }

        if (
            ($this->shareByDefault && !isset($this->shared[$cName]))
            || (isset($this->shared[$cName]) && $this->shared[$cName] === true)
        ) {
            $this->instances[$cName] = $instance;
        }

        if (count($this->interfaceInitializer)) {
            foreach($this->interfaceInitializer as $interfaceInitializer) {
                $interfaceInitializer->initialize($instance, $cName);
            }
        }

        end:
        if (is_object($instance) and $instance instanceof InvokeInterface)
            $instance->invoke($this->getServiceLocator());
        return $instance;
    }

    /**
     * Determine if we have an alias
     *
     * @param  string $alias
     * @return bool
     */
    public function hasAlias($alias)
    {
        return isset($this->aliases[$this->canonicalizeName($alias)]);
    }

    /**
     * @param  string|array  $name
     * @param  bool          $checkAbstractFactories
     * @param  bool          $usePeeringServiceManagers
     * @return bool
     */
    public function has($name, $checkAbstractFactories = true, $usePeeringServiceManagers = true)
    {
        if (!$this->initConfigMade) {
            $this->initConfigMade = true;
            $this->initServicesFromConfig();
        }

        if (is_array($name)) {
            list($cName, $rName) = $name;
        } else {
            $rName = $name;

            // inlined code from ServiceManager::canonicalizeName for performance
            if (isset($this->canonicalNames[$rName])) {
                $cName = $this->canonicalNames[$name];
            } else {
                $cName = $this->canonicalizeName($name);
            }
        }

        if (
            isset($this->invokableClasses[$cName])
            || isset($this->factories[$cName])
            || isset($this->aliases[$cName])
            || isset($this->instances[$cName])
        ) {
            return true;
        }

        return false;
    }

    public function setService($name, $service)
    {
        $name = $this->canonicalizeName($name);

        if ($this->has(array($name, $name), false)) {
            if ($this->allowOverride === false) {
                throw new Exception(sprintf(
                    'Сервис с именем или алиасом "%s" уже существует в системе и не может быть перезаписан; используйте другое имя.',
                    $name
                ));
            }
            $this->unregisterService($name);
        }

        $this->instances[$name] = $service;
        return $this;
    }

    /**
     * @param  string $alias
     * @param  string $nameOrAlias
     * @return ServiceManager
     * @throws Exception
     */
    public function setAlias($alias, $nameOrAlias)
    {
        if (!is_string($alias) || !is_string($nameOrAlias)) {
            throw new Exception('Service or alias names must be strings.');
        }

        $cAlias = $this->canonicalizeName($alias);
        $nameOrAlias = $this->canonicalizeName($nameOrAlias);

        if ($alias == '' || $nameOrAlias == '') {
            throw new Exception('Invalid service name alias');
        }

        if ($this->allowOverride === false && $this->has(array($cAlias, $alias), false)) {
            throw new Exception(sprintf(
                'An alias by the name "%s" or "%s" already exists',
                $cAlias,
                $alias
            ));
        }

        $this->aliases[$cAlias] = $nameOrAlias;
        return $this;
    }

    protected function canonicalizeName($name)
    {
        if (isset($this->canonicalNames[$name])) {
            return $this->canonicalNames[$name];
        }

        // this is just for performance instead of using str_replace
        return $this->canonicalNames[$name] = strtolower(strtr($name, $this->canonicalNamesReplacements));
    }


    /**
     * Set factory
     *
     * @param  string                           $name
     * @param  string|FactoryInterface|callable $factory
     * @param  bool                             $shared
     * @return ServiceManager
     * @throws Exception
     * @throws Exception
     */
    public function setFactory($name, $factory, $shared = null)
    {
        $cName = $this->canonicalizeName($name);

        if (!($factory instanceof FactoryInterface || is_string($factory) || is_callable($factory))) {
            throw new Exception(
                'Provided abstract factory must be the class name of an abstract factory or an instance of an AbstractFactoryInterface.'
            );
        }

        if (in_array($cName, $this->serviceFixed))
            return $this;

        if ($this->has(array($cName, $name), false)) {
            if ($this->allowOverride === false) {
                throw new Exception(sprintf(
                    'A service by the name or alias "%s" already exists and cannot be overridden, please use an alternate name',
                    $name
                ));
            }
            $this->unregisterService($cName);
        }

        if ($shared === null) {
            $shared = $this->shareByDefault;
        }

        $this->factories[$cName] = $factory;
        $this->shared[$cName]    = (bool) $shared;

        return $this;
    }


    /**
     * Фиксирование сервиса.
     *
     * @param $name
     * @return $this
     */
    public function setServiceFixed($name)
    {
        $cName = $this->canonicalizeName($name);
        if (!in_array($cName, $this->serviceFixed))
            $this->serviceFixed[] = $cName;
        return $this;
    }
    /**
     * Установка имени класса для создания.
     *
     * @param  string  $name
     * @param  string  $invokableClass
     * @param  bool $shared
     * @return ServiceManager
     * @throws Exception
     */
    public function setInvokableClass($name, $invokableClass, $shared = null)
    {
        $cName = $this->canonicalizeName($name);

        if (in_array($cName, $this->serviceFixed))
            return $this;

        if ($this->has(array($cName, $name), false)) {
            if ($this->allowOverride === false) {
                throw new Exception(sprintf(
                    'Сервис с именем или алиасом "%s" уже существует в системе и не может быть перезаписан; используйте другое имя.',
                    $name
                ));
            }
            $this->unregisterService($cName);
        }

        if ($shared === null) {
            $shared = $this->shareByDefault;
        }

        $this->invokableClasses[$cName] = $invokableClass;
        $this->shared[$cName]         = (bool) $shared;

        return $this;
    }

    /**
     * @param  string $name
     * @param  bool   $isShared
     * @return ServiceManager
     * @throws Exception
     */
    public function setShared($name, $isShared)
    {
        $cName = $this->canonicalizeName($name);

        if (
            !isset($this->invokableClasses[$cName])
            && !isset($this->factories[$cName])
        ) {
            throw new Exception(sprintf(
                '%s: A service by the name "%s" was not found and could not be marked as shared',
                get_class($this) . '::' . __FUNCTION__,
                $name
            ));
        }

        $this->shared[$cName] = (bool) $isShared;
        return $this;
    }

    /**
     * Retrieve a keyed list of all registered services. Handy for debugging!
     *
     * @return array
     */
    public function getRegisteredServices()
    {
        return array(
            'invokableClasses' => array_keys($this->invokableClasses),
            'factories' => array_keys($this->factories),
            'aliases' => array_keys($this->aliases),
            'instances' => array_keys($this->instances),
        );
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Create an instance of the requested service
     *
     * @param  string|array $name
     *
     * @return bool|object
     */
    public function create($name)
    {
        if (is_array($name)) {
            list($cName, $rName) = $name;
        } else {
            $rName = $name;

            // inlined code from ServiceManager::canonicalizeName for performance
            if (isset($this->canonicalNames[$rName])) {
                $cName = $this->canonicalNames[$name];
            } else {
                $cName = $this->canonicalizeName($name);
            }
        }

        return $this->doCreate($rName, $cName);
    }

    /**
     * Actually creates the service
     *
     * @param string $rName real service name
     * @param string $cName canonicalized service name
     *
     * @return bool|mixed|null|object
     * @throws Exception
     *
     * @internal this method is internal because of PHP 5.3 compatibility - do not explicitly use it
     */
    public function doCreate($rName, $cName)
    {
        $instance = false;

        if (isset($this->factories[$cName])) {
            $instance = $this->createFromFactory($cName, $rName);
        }

        if ($instance === false && isset($this->invokableClasses[$cName])) {
            $instance = $this->createFromInvokable($cName, $rName);
        }


        if ($instance === false && $this->throwExceptionInCreate) {
            throw new Exception(sprintf(
                'No valid instance was found for %s%s',
                $cName,
                ($rName ? '(alias: ' . $rName . ')' : '')
            ));
        }

        return $instance;
    }


    /**
     * Attempt to create an instance via an invokable class
     *
     * @param  string $canonicalName
     * @param  string $requestedName
     * @return null|\stdClass
     * @throws Exception If resolved class does not exist
     */
    protected function createFromInvokable($canonicalName, $requestedName)
    {
        $invokable = $this->invokableClasses[$canonicalName];
        if (!class_exists($invokable)) {
            throw new Exception(sprintf(
                '%s: failed retrieving "%s%s" via invokable class "%s"; class does not exist',
                get_class($this) . '::' . __FUNCTION__,
                $canonicalName,
                ($requestedName ? '(alias: ' . $requestedName . ')' : ''),
                $invokable
            ));
        }
        $instance = new $invokable;
        return $instance;
    }

    /**
     * Attempt to create an instance via a factory
     *
     * @param  string $canonicalName
     * @param  string $requestedName
     * @return mixed
     * @throws Exception If factory is not callable
     */
    protected function createFromFactory($canonicalName, $requestedName)
    {
        $factory = $this->factories[$canonicalName];
        if (is_string($factory) && class_exists($factory, true)) {
            $factory = new $factory;
            $this->factories[$canonicalName] = $factory;
        }
        if ($factory instanceof FactoryInterface) {
            $instance = $this->createServiceViaCallback(array($factory, 'createService'), $canonicalName, $requestedName);
        } elseif (is_callable($factory)) {
            $instance = $this->createServiceViaCallback($factory, $canonicalName, $requestedName);
        } else {
            throw new Exception(sprintf(
                'While attempting to create %s%s an invalid factory was registered for this instance type.',
                $canonicalName,
                ($requestedName ? '(alias: ' . $requestedName . ')' : '')
            ));
        }
        return $instance;
    }

    /**
     * Create service via callback
     *
     * @param  callable $callable
     * @param  string   $cName
     * @param  string   $rName
     * @throws Exception
     * @return object
     */
    protected function createServiceViaCallback($callable, $cName, $rName)
    {
        static $circularDependencyResolver = array();
        $depKey = spl_object_hash($this->getServiceLocator()) . '-' . $cName;

        if (isset($circularDependencyResolver[$depKey])) {
            $circularDependencyResolver = array();
            throw new Exception('Circular dependency for LazyServiceLoader was found for instance ' . $rName);
        }

        try {
            $circularDependencyResolver[$depKey] = true;
            $instance = call_user_func($callable, $this->getServiceLocator(), $cName, $rName);
            unset($circularDependencyResolver[$depKey]);
        } catch (Exception $e) {
            unset($circularDependencyResolver[$depKey]);
            throw $e;
        } catch (\Exception $e) {
            unset($circularDependencyResolver[$depKey]);
            throw new Exception(
                sprintf('An exception was raised while creating "%s"; no instance returned', $rName),
                $e->getCode(),
                $e
            );
        }
        if ($instance === null) {
            throw new Exception('The factory was called but did not return an instance.');
        }

        return $instance;
    }


    /**
     * Unregister a service
     *
     * Called when $allowOverride is true and we detect that a service being
     * added to the instance already exists. This will remove the duplicate
     * entry, and also any shared flags previously registered.
     *
     * @param  string $canonical
     * @return void
     */
    protected function unregisterService($canonical)
    {
        $types = array('invokableClasses', 'factories', 'aliases');
        foreach ($types as $type) {
            if (isset($this->{$type}[$canonical])) {
                unset($this->{$type}[$canonical]);
                break;
            }
        }

        if (isset($this->instances[$canonical])) {
            unset($this->instances[$canonical]);
        }

        if (isset($this->shared[$canonical])) {
            unset($this->shared[$canonical]);
        }
    }

    /**
     * Возвращает справочную информацию о зарегистрированных хелперах.
     * @return string
     */
    public function help()
    {
        ob_start();
        ?><h3>invokableClasses</h3><?
        echo '<pre>';
        print_r($this->invokableClasses);
        echo '</pre>';
        ?><h3>factories</h3><?
        echo '<pre>';
        print_r($this->factories);
        echo '</pre>';
        ?><h3>aliases</h3><?
        echo '<pre>';
        print_r($this->aliases);
        echo '</pre>';

        $result = ob_get_clean();

        return $result;
    }

} 