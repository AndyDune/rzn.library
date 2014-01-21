<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20.01.14
 * Time: 12:25
 */

namespace Rzn\Library\ServiceManager;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;


interface ServiceLocatorAwareInterface {
    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator);

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator();

} 