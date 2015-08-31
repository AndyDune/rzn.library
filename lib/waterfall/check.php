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
            if (isset($item['invokable'])) {
                if(class_exists($item['invokable'])) {
                    $streamDescription['drops'][$dropName]['exist'] = 'Класс существует';
                } else {
                    $streamDescription['drops'][$dropName]['exist'] = 'Класс НЕ существует';
                }
            } else if (isset($item['service'])) {
                $name = $item['service'];
                if ($this->getServiceLocator()->has($name)) {
                    $streamDescription['drops'][$dropName]['exist'] = 'Сервис существует';
                } else {
                    $streamDescription['drops'][$dropName]['exist'] = 'Сервис НЕ существует';
                }
            }
        }

        // Проверка конечной функции
        if (isset($streamDescription['final'])) {
            $item = $streamDescription['final'];
            if (isset($item['invokable'])) {
                if(class_exists($item['invokable'])) {
                    $streamDescription['final']['exist'] = 'Класс существует';
                } else {
                    $streamDescription['final']['exist'] = 'Класс НЕ существует';
                }
            } else if (isset($item['service'])) {
                $name = $item['service'];
                if ($this->getServiceLocator()->has($name)) {
                    $streamDescription['final']['exist'] = 'Сервис существует';
                } else {
                    $streamDescription['final']['exist'] = 'Сервис НЕ существует';
                }
            }
        }
        // Проверка функции ошибки
        if (isset($streamDescription['error'])) {
            $item = $streamDescription['error'];
            if (isset($item['invokable'])) {
                if(class_exists($item['invokable'])) {
                    $streamDescription['error']['exist'] = 'Класс существует';
                } else {
                    $streamDescription['error']['exist'] = 'Класс НЕ существует';
                }
            } else if (isset($item['service'])) {
                $name = $item['service'];
                if ($this->getServiceLocator()->has($name)) {
                    $streamDescription['error']['exist'] = 'Сервис существует';
                } else {
                    $streamDescription['error']['exist'] = 'Сервис НЕ существует';
                }
            }
        }

        // Функция запускаемая после остановки водопада
        if (isset($streamDescription['stop'])) {
            $item = $streamDescription['stop'];
            if (isset($item['invokable'])) {
                if(class_exists($item['invokable'])) {
                    $streamDescription['stop']['exist'] = 'Класс существует';
                } else {
                    $streamDescription['stop']['exist'] = 'Класс НЕ существует';
                }
            } else if (isset($item['service'])) {
                $name = $item['service'];
                if ($this->getServiceLocator()->has($name)) {
                    $streamDescription['stop']['exist'] = 'Сервис существует';
                } else {
                    $streamDescription['stop']['exist'] = 'Сервис НЕ существует';
                }
            }

        }
        pr($streamDescription);
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
}