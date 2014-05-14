<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
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

        if ($object instanceof SessionServiceInterface)
            $object->setSessionService($this->sm->get('session'));

    }
} 