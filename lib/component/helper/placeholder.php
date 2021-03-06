<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;


use Rzn\Library\Exception as InvalidArgumentException;
use Rzn\Library\Component\Helper\Placeholder\Container;

/**
 * Helper for passing data between otherwise segregated Views. It's called
 * Placeholder to make its typical usage obvious, but can be used just as easily
 * for non-Placeholder things. That said, the support for this is only
 * guaranteed to effect subsequently rendered templates, and of course Layouts.
 */
class Placeholder extends HelperAbstract
{
    /**
     * Placeholder items
     *
     * @var array
     */
    protected $items = array();

    /**
     * Default container class
     * @var string
     */
    protected $containerClass = 'Rzn\Library\Component\Helper\Placeholder\Container';

    /**
     * Placeholder helper
     *
     * @param  string $name
     * @throws InvalidArgumentException
     * @return Placeholder\Container\AbstractContainer
     */
    public function __invoke($name = null)
    {
        if ($name == null) {
            throw new InvalidArgumentException('Placeholder: missing argument.  $name is required by placeholder($name)');
        }

        $name = (string) $name;
        return $this->getContainer($name);
    }

    /**
     * createContainer
     *
     * @param  string $key
     * @param  array $value
     * @return Container\AbstractContainer
     */
    public function createContainer($key, array $value = array())
    {
        $key = (string) $key;

        $this->items[$key] = new $this->containerClass($value);
        return $this->items[$key];
    }

    /**
     * Retrieve a placeholder container
     *
     * @param  string $key
     * @return Container\AbstractContainer
     */
    public function getContainer($key)
    {
        $key = (string) $key;
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        $container = $this->createContainer($key);

        return $container;
    }

    /**
     * Does a particular container exist?
     *
     * @param  string $key
     * @return bool
     */
    public function containerExists($key)
    {
        $key = (string) $key;
        $return =  array_key_exists($key, $this->items);
        return $return;
    }
}
