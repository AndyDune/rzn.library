<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.12.13
 * Time: 12:22
 */

namespace Rzn\Library\ServiceManager;
use Rzn\Library\Registry;

class InterfaceInitializer implements InitializerInterface
{
    /**
     * @var \Rzn\Library\ServiceManager
     */
    protected $sm;

    protected $registry;

    /**
     * @param \Rzn\Library\ServiceManager $sm
     */
    public function __construct(\Rzn\Library\ServiceManager $sm)
    {
        $this->registry = Registry::getInstance();
        $this->sm = $sm;
    }

    public function initialize($object)
    {
        if (!is_object($object))
            return false;
        $reg = $this->registry;
        if ($object instanceof BitrixApplicationInterface)
            $object->setApplication($reg->getGlobal('APPLICATION'));
        if ($object instanceof BitrixDbInterface)
            $object->setDb($reg->getGlobal('DB'));
        if ($object instanceof BitrixUserInterface)
            $object->setUser($reg->getGlobal('USER'));
        if ($object instanceof ServiceLocatorAwareInterface)
            $object->setServiceLocator($this->sm);
    }
} 