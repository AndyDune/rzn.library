<?
/**
 * Класс регистри.
 *
 * Class Registry
 */
namespace Rzn\Library;
class Registry extends \ArrayObject
{


    /**
     * Class name of the singleton registry object.
     * @var string
     */
    private static $_registryClassName = 'Rzn\\Library\\Registry';

    /**
     * Registry object provides storage for shared objects.
     * @var Registry
     */
    private static $_registry = null;


    /**
     * Менеджер сервисов сайта.
     *
     * @var ServiceManager
     */
    private static $_sm = null;

    /**
     * Retrieves the default registry instance.
     *
     * @return Registry
     */
    public static function getInstance()
    {
        if (self::$_registry === null)
        {
            self::init();
        }

        return self::$_registry;
    }

    /**
     * Initialize the default registry instance.
     *
     * @return void
     */
    protected static function init()
    {
        self::setInstance(new self::$_registryClassName());
    }


    /**
     * Возвращает менеджер сервисов.
     *
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        if (!self::$_sm)
            self::$_sm = new ServiceManager();
        return self::$_sm;
    }

    /**
     * Set the class name to use for the default registry instance.
     * Does not affect the currently initialized instance, it only applies
     * for the next time you instantiate.
     *
     * @param string $registryClassName
     * @return void
     * @throws \Exception if the registry is initialized or if the
     *   class name is not valid.
     */
    public static function setClassName($registryClassName = 'Rzn\\Library\\Registry')
    {
        if (self::$_registry !== null)
        {
            throw new \Exception('Registry is already initialized');
        }

        if (!is_string($registryClassName)) {
            throw new \Exception("Argument is not a class name");
        }

        if (!class_exists($registryClassName))
        {
            throw new \Exception("No class.");
        }

        self::$_registryClassName = $registryClassName;
    }

    /**
     * Unset the default registry instance.
     * Primarily used in tearDown() in unit tests.
     * @returns void
     */
    public static function _unsetInstance()
    {
        self::$_registry = null;
    }

    /**
     * Set the default registry instance to a specified instance.
     *
     * @param Registry $registry An object instance of type Registry,
     *   or a subclass.
     * @return void
     * @throws \Exception if registry is already initialized.
     */
    public static function setInstance(Registry $registry)
    {
        if (self::$_registry !== null)
        {
            throw new \Exception('Registry is already initialized');
        }

        self::setClassName(get_class($registry));
        self::$_registry = $registry;
    }

    /**
     * @return \CUser
     */
    public static function getUser()
    {
        global $USER;
        return $USER;
    }

    /**
     * @return object
     */
    public static function getGlobal($key)
    {
        return $GLOBALS[$key];
    }


    /**
     * getter method, basically same as offsetGet().
     *
     * This method can be called from an object of type Registry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index - get the value associated with $index
     * @return mixed
     * @throws \Exception if no entry is registerd for $index.
     */
    public static function get($index)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index))
        {
            if ($index == 'ROOT' or $index == 'DOCUMENT_ROOT')
                return $_SERVER['DOCUMENT_ROOT'];

            return null;
            throw new \Exception("No entry is registered for key '$index'");
        }

        $value = $instance->offsetGet($index);

        return $value;
    }

    /**
     * setter method, basically same as offsetSet().
     *
     * This method can be called from an object of type Registry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index The location in the ArrayObject in which to store
     *   the value.
     * @param mixed $value The object to store in the ArrayObject.
     * @return void
     */
    public static function set($index, $value, $lock = false)
    {
        $instance = self::getInstance();
        $instance->offsetSet($index, $value, $lock);
    }


    protected $_lockedValues = array();


    public function offsetSet($index, $newval, $lock = false)
    {
        if (in_array($index, $this->_lockedValues))
            return false;
        if ($lock)
            $this->_lockedValues[] = $index;
        return parent::offsetSet($index, $newval);
    }

    /**
     * Returns TRUE if the $index is a named value in the registry,
     * or FALSE if $index was not found in the registry.
     *
     * @param  string $index
     * @return boolean
     */
    public static function isRegistered($index)
    {
        if (self::$_registry === null)
        {
            return false;
        }
        return self::$_registry->offsetExists($index);
    }

    /**
     * Constructs a parent ArrayObject with default
     * ARRAY_AS_PROPS to allow acces as an object
     *
     * @param array $array data array
     * @param integer $flags ArrayObject flags
     */
    public function __construct($array = array(), $flags = parent::ARRAY_AS_PROPS)
    {
        parent::__construct($array, $flags);
    }

    /**
     * @param string $index
     * @returns mixed
     *
     * Workaround for http://bugs.php.net/bug.php?id=40442 (ZF-960).
     */
    public function offsetExists($index)
    {
        return array_key_exists($index, $this);
    }

}
