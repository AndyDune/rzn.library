<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 25.08.2015                                      
  * ----------------------------------------------------
  *
  * Инъекция сервиса из собственного менеджера.
  *
  * Пример использования:
  *
    'injector' => [
        'injectModel' => [
            'handler' => 'setter',
            'options' => [
                'set' => 'custom_service',
                'manager' => 'models', // Имя менеджера
                'service' => 'messages', // Имя сервиса
                'method' => 'setMessagesModel'
            ]
        ],
    ]
*/

namespace Rzn\Library\Injector\Handler\SetterHandler;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\Injector\Exception;

class CustomService implements ServiceLocatorAwareInterface
{
    protected $customServiceManager;

    public function execute($object, $params)
    {
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'setCustomService';
        }
        if (!isset($params['manager']) or !isset($params['service'])) {
            throw new Exception('Для инъектора не указаны обязательные параметры: manager и(или) service');
        }
        call_user_func([$object, $method],
            $this->customServiceManager->get($params['manager'])->get($params['service']));
    }

    /**
     * Проверка
     *
     * @param $object
     * @param $params
     * @return array
     */
    public function check($object, $params)
    {
        $errors = [];
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'setCustomService';
        }
        if (!method_exists($object, $method)) {
            $errors[] = 'Субъект инъекции (' . get_class($object) . ') не имеет целевого метода: ' . $method;
        }
        if (!isset($params['manager']) or !isset($params['service'])) {
            $errors[] = 'Для инъектора не указаны обязательные параметры: manager и(или) service';
            return $errors;
        }
        if (!$this->customServiceManager->has($params['manager'])) {
            $errors[] = 'Собственного менеджера сервисов не существует: ' . $params['manager'];
            return $errors;
        }

        if (!$this->customServiceManager->get($params['manager'])->has($params['service'])) {
            $errors[] = 'Сервиса ' . $params['service'] . ' в собственном менеджере сервисов ' . $params['manager'] . ' не существует';
        }

        return $errors;
    }

    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->customServiceManager = $serviceLocator->get('custom_service_managers');
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->customServiceManager;
    }
}