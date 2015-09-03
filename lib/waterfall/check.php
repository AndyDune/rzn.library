<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 28.08.2015                                      
  * ----------------------------------------------------
  *
  * Проверка параметров водопада.
  */


namespace Rzn\Library\Waterfall;
use Rzn\Library\Config;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;


class Check implements ServiceLocatorAwareInterface, ConfigServiceAwareInterface
{

    protected $serviceManager;

    /**
     * Объект конфига полностью
     *
     * @var \Rzn\Library\Config
     */
    protected $configService;

    /**
     * Часть объекта конфига под ключем waterfall
     *
     * @var \Rzn\Library\Config
     */
    protected $waterfallConfig;

    /**
     * @var \Rzn\Library\Waterfall\WaterfallCollection
     */
    protected $waterfall;


    /**
     * @var \Rzn\Library\Injector\Check
     */
    protected $injectorCheck;


    /**
     * Быстрая проверка конфига водопада с печатью отчета.
     * Отчет - это массив с метками.
     * Проверяет на существование указанных классов и сервисов.
     * По напечатанному массиву можно определять окончательный набор параметров
     *
     * Если в качестве параметра указана строка, то она определяет имя водопада в основном конфиге.
     * Оттуда и выбирается описание
     *
     * @param string|array|Config $streamDescription
     */
    public function checkStream($streamDescription)
    {
        $errors = ['drops' => [], 'final' => 'NO', 'error' => 'NO', 'stop' => 'NO'];
        if (is_string($streamDescription)) {
            // Загрузка и сохранение водопада
            if (!isset($this->waterfallConfig['streams'][$streamDescription])) {
                echo 'Водопад не найден: ' . $streamDescription;
                return;
            }
            $streamDescription = $this->waterfallConfig['streams'][$streamDescription];
            if ($streamDescription instanceof Config) {
                $streamDescription = $streamDescription->toArray();
            }
        }

        foreach($streamDescription['drops'] as $dropName => $item) {
            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['drops'][$dropName] = $result;
            } else {
                $errors['drops'][$dropName] = 'OK';
            }
        }

        // Проверка конечной функции
        if (isset($streamDescription['final'])) {
            $item = $streamDescription['final'];

            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['final'] = $result;
            } else {
                $errors['final'] = 'OK';
            }

        }
        // Проверка функции ошибки
        if (isset($streamDescription['error'])) {
            $item = $streamDescription['error'];

            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['error'] = $result;
            } else {
                $errors['error'] = 'OK';
            }

        }

        // Функция запускаемая после остановки водопада
        if (isset($streamDescription['stop'])) {
            $item = $streamDescription['stop'];

            $result = $this->checkFunctionDescription($item);
            if ($result) {
                $errors['stop'] = $result;
            } else {
                $errors['stop'] = 'OK';
            }

        }
        return $errors;
    }

    public function checkFunctionDescription($item)
    {
        $errors = [];
        $object = null;
        if (isset($item['invokable'])) {
            if(!class_exists($item['invokable'])) {
                $errors[] = 'Класс НЕ существует: ' . $item['invokable'];
            } else {
                $class = $item['invokable'];
                $object = new $class();
            }
        } else if (isset($item['service'])) {
            $name = $item['service'];
            if (!$this->getServiceLocator()->has($name)) {
                $errors[] = 'Сервис НЕ существует: ' . $name;
            } else {
                $object = $this->getServiceLocator()->get($name);
            }
        } else {
            $errors[] = 'Не указан обязательный ключ в описании';
        }
        if ($object) {
            if (isset($item['injector'])) {
                $this->getInjectorCheck()->inject($object, $item['injector']);
                $errors['injector'] = $this->getInjectorCheck()->getCheckResult();
            }
        }

        return $errors;
    }

    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {

        if ($service['waterfall']) {
            $this->waterfallConfig = $service['waterfall'];
        }
        $this->configService = $service;
    }

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->configService;
    }
    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceManager;
    }

    /**
     * @param \Rzn\Library\Waterfall\WaterfallCollection $waterfall
     */
    public function setWaterfall($waterfall)
    {
        $this->waterfall = $waterfall;
    }

    /**
     * @return \Rzn\Library\Waterfall\WaterfallCollection
     */
    public function getWaterfall()
    {
        return $this->waterfall;
    }

    public function setInjectorCheck($service)
    {
        $this->injectorCheck = $service;
    }

    public function getInjectorCheck()
    {
        return $this->injectorCheck;
    }

}