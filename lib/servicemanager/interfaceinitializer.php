<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 *
 * Базовый инициилизатор для базовых сервисов и хелперов.
 * Применение описывается в конфигурационнои массиве.
 *
 * Новых инъекций сюда не добавлять - использовать систему инициилизаторов.
 *
 */

namespace Rzn\Library\ServiceManager;

use Rzn\Library\Registry;

class InterfaceInitializer implements InitializerInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $sm;

    protected $registry;

    /**
     * @param ServiceManager $sm
     */
    public function __construct($sm = null)
    {
        $this->sm = $sm;
        $this->registry = Registry::getInstance();
    }

    public function initialize($object, $name)
    {
        if (!is_object($object))
            return false;
        $reg = $this->registry;

        if ($object instanceof BitrixApplicationInterface) {
            $object->setApplication($reg->getGlobal('APPLICATION'));
        }

        if ($object instanceof BitrixDbInterface) {
            $object->setDb($reg->getGlobal('DB'));
        }

        if ($object instanceof BitrixUserInterface) {
            $object->setUser($reg->getGlobal('USER'));
        }

        if ($object instanceof ServiceLocatorAwareInterface) {
            $object->setServiceLocator($this->sm);
        }

        if ($object instanceof SessionServiceInterface) {
            $object->setSessionService($this->sm->get('session'));
        }
    }

    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->sm;
    }

} 