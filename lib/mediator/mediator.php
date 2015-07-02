<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 05.06.2015                                      
  * ----------------------------------------------------
  *
  *
  * todo Добавить комментарии
  * todo Протестировать
  */


namespace Rzn\Library\Mediator;

use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\Waterfall\WaterfallAwareInterface;

class Mediator implements ConfigServiceAwareInterface, ServiceLocatorAwareInterface, WaterfallAwareInterface
{
    protected $channels = [];

    protected $loadedChannels = [];

    /**
     * @var \Rzn\Library\Config $service
     */
    protected $configService;

    protected $serviceLocator;

    /**
     * Хранит массив с конфигурацией медиатора.
     *
     * @var array
     */
    protected $mediatorConfig = [];

    protected $sharedLoader = [];

    /**
     * @var \Rzn\Library\Waterfall\WaterfallCollection
     */
    protected $waterfall;
    /**
     * Загрузка слушателей канала из конфига.
     *
     *
     * @param $channel
     * @return $this
     */
    protected function load($channel)
    {
        if (in_array($channel, $this->loadedChannels)) {
            return $this;
        }

        if (isset($this->mediatorConfig['channels'][$channel])) {
            $this->loadedChannels[] = $channel;
            $channelRetriever = $this->mediatorConfig['channels'][$channel];
                // Возвращает функцию - слушателя канала.
                if ($channelRetriever['waterfall']) {
                    $mediator = $this;
                    $this->subscribe($channel, function ($arguments) use ($channelRetriever, $mediator) {
                        return $mediator->getWaterfall()->execute($channelRetriever['waterfall'], $arguments);
                    });
                } else {
                    $service = $this->getObjectIfShared($channelRetriever);
                    if (isset($channelRetriever['method'])) {
                        // Указан явно метод слушателя, который надо запустить
                        $this->subscribe($channel, function ($arguments) use ($channelRetriever, $service) {
                            return call_user_func([$service, $channelRetriever['method']], $arguments);
                        });
                    } else if ($service instanceof MediatorInitializationAwareInterface) {
                        // Класс слушателя имеет нужный интрефейс для выборки слушающей функции
                        $this->subscribe($channel, $service->getFunctionForMediatorSubscribe($channel));
                    } else if (is_callable($service)) {
                        // Класс объекта имеет метод __invoke
                        $this->subscribe($channel, function ($arguments) use ($service) {
                            return call_user_func($service, $arguments);
                        });

                    } else {
                        new Exception('Некорректное описание канала: ' . $channel, 3);
                        // todo Добавить исключение
                    }
                }
        }

        return $this;
    }

    /**
     * @param $channelRetriever
     * @return object
     */
    protected function getObjectIfShared($channelRetriever)
    {
        if (array_key_exists($channelRetriever, $this->sharedLoader)) {
            return $this->sharedLoader[$channelRetriever];
        }
        if (isset($channelRetriever['service'])) {
            $name = $channelRetriever['service'];
            $object = $this->getServiceLocator()->get($name);
        } else if (isset($channelRetriever['invokable'])) {
            $name = $channelRetriever['invokable'];
            $object = new $name();
        }
        if ($channelRetriever['shared'] !== false) {
            $this->sharedLoader[$name] = $object;
        }
        return $object;
    }


    public function subscribe($channel, $function)
    {
        if (isset($this->channels[$channel])) {
            new Exception('Повторное объявление слушателя канала.', 1);
            // Генерация ошибки при повторной инициилизации канала
        }
        $this->channels[$channel] = $function;
        return $this;
    }

    public function publish($channel, $arguments = null)
    {
        $result = null;
        // Перед запуском канала смотрим слушателе его в конфиге
        $this->load($channel);
        if (!isset($this->channels[$channel])) {
            new Exception('Канал не существует.', 2);
        }
        return call_user_func($this->channels[$channel], $arguments);
    }

    /**
     * Проверка существования слушателя канала.
     *
     * @param $channel имя канала
     * @return bool
     */
    public function has($channel)
    {
        if (isset($this->channels[$channel])) {
            return true;
        }
        return false;
    }

    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {
        if ($service['mediator']) {
            $this->mediatorConfig = $service['mediator'];
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
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return \Rzn\Library\Waterfall\WaterfallCollection
     */
    public function getWaterfall()
    {
        return $this->waterfall;
    }

    /**
     * Внедрение водопала делается инъектором
     * @param $waterfall
     */
    public function setWaterfall($waterfall)
    {
        $this->waterfall = $waterfall;
    }


}