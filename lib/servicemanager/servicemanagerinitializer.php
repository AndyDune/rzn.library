<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 11.12.2017                            |
 * -----------------------------------------------
 *
 */


namespace Rzn\Library\ServiceManager;


class ServiceManagerInitializer implements ServiceLocatorAwareInterface
{
    protected $sm;


    public function __invoke($instance)
    {
        $this->initialize($instance);
    }

    public function initialize($instance)
    {
        $this->getServiceLocator()->executeInitialize($instance);
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