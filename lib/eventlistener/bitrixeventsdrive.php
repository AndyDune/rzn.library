<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 17.12.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\EventListener;


use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;
use Rzn\Library\Registry;
use Rzn\Library\Config;

use Bitrix\Main\EventManager;


use ArrayObject;
/**
 * Class BitrixEventsDrive
 * @package Rzn\Library\EventListener
 */
class BitrixEventsDrive implements ServiceLocatorAwareInterface
{
    /**
     * @var \Rzn\Library\ServiceManager
     */
    protected static $serviceManager;

    /**
     * @var \Rzn\Library\EventManager\EventManager
     */
    protected static $eventManager;

    /**
     * Передается всем запускаемым из объекта этого класса событиям
     *
     * @var $this
     */
    protected static $instance;


    protected $sharedParams = [];

    protected $bitrixEvents = [];

    /**
     * @param $e \Rzn\Library\EventManager\Event
     */
    public function __invoke($e)
    {
        $eventManager = EventManager::getInstance();
        // user.login.after -> main_OnAfterUserLogin
        $eventManager->addEventHandlerCompatible("main", "OnAfterUserLogin", array(__CLASS__, 'onAfterUserLogin'), false, 100);

        // user.login.before -> main_OnBeforeUserLogin
        $eventManager->addEventHandlerCompatible("main", "OnBeforeUserLogin", array(__CLASS__, 'onBeforeUserLogin'), false, 100);

        // main_OnAfterUserRegister
        $eventManager->addEventHandlerCompatible("main", "OnAfterUserRegister", array(__CLASS__, 'onAfterUserRegister'), false, 100);

        // user.register.before -> main_OnBeforeUserRegister
        $eventManager->addEventHandlerCompatible("main", "OnBeforeUserRegister", array(__CLASS__, 'onBeforeUserRegister'), false, 100);

        // main_OnBuildGlobalMenu
        $eventManager->addEventHandlerCompatible("main", "OnBuildGlobalMenu", array(__CLASS__, 'OnBuildGlobalMenu'), false, 100);

        // iblock_OnBeforeIBlockElementUpdate
        $eventManager->addEventHandlerCompatible("iblock", "OnBeforeIBlockElementUpdate", array(__CLASS__, 'OnBeforeIBlockElementUpdate'), false, 100);

        // iblock.element.add.before -> iblock_OnBeforeIBlockElementAdd
        $eventManager->addEventHandlerCompatible("iblock", "OnBeforeIBlockElementAdd", array(__CLASS__, 'OnBeforeIBlockElementAdd'), false, 100);

        // main_OnBeforeLocalRedirect
        $eventManager->addEventHandlerCompatible("main", "OnBeforeLocalRedirect", array(__CLASS__, 'OnBeforeLocalRedirect'), false, 100);



        // iblock_OnStartIBlockElementAdd
        $eventManager->addEventHandlerCompatible("iblock", "OnStartIBlockElementAdd", array(__CLASS__, 'OnStartIBlockElementAdd'), false, 100);

        // iblock_OnStartIBlockElementUpdate
        $eventManager->addEventHandlerCompatible("iblock", "OnStartIBlockElementUpdate", array(__CLASS__, 'OnStartIBlockElementUpdate'), false, 100);


        // sale_OnSaleComponentOrderOneStepPaySystem
        $eventManager->addEventHandlerCompatible("sale", "OnSaleComponentOrderOneStepPaySystem",
            array(__CLASS__, 'onSaleComponentOrderOneStepPaySystem'), false, 100);

        // Встраивать в bitrix_event
        //$eventManager->addEventHandlerCompatible("main", "OnPageStart", array(__CLASS__, 'onPageStart'), false, 100);
        //$eventManager->addEventHandlerCompatible("main", "OnAfterEpilog", array(__CLASS__, 'onAfterEpilog'), false, 100);

        // Экспериментальный функционал
        $config = $this->getServiceLocator()->get('config');

        if ($config['bitrix_events'] and $config['bitrix_events'] instanceof Config) {
            $this->bitrixEvents = $config['bitrix_events']->toArray();
            $this->registerBitrixEvents();
        }

        self::$instance = $this;
        return null;
    }


    /**
     * Регистрация в событийной модели битрикса обработчикой адаптера.
     */
    protected function registerBitrixEvents()
    {
        $eventManager = EventManager::getInstance();

        /** @var \Rzn\Library\EventManager\EventManager  $events */
        $events = $this->getServiceLocator()->get('event_manager');
        foreach ($this->bitrixEvents as $eventModule => $params) {
            if (!$params) {
                continue;
            }
            foreach ($params as $eventName => $listener) {
                //$methodName = 'bitrixEvent_' . $eventModule . '_' . $eventName;
                // Совместного использования с продолженным событиями
                $methodName = $eventModule . '_' . $eventName;
                $eventManager->addEventHandlerCompatible($eventModule, $eventName, array(__CLASS__, $methodName), false, 100);
            }
        }
    }

    protected function registerListeners($module = null, $name = null)
    {
        /** @var \Rzn\Library\EventManager\EventManager  $events */
        $events = $this->getServiceLocator()->get('event_manager');
        foreach ($this->bitrixEvents as $eventModule => $params) {
            if (!$params) {
                continue;
            }
            if ($module and $module != $eventModule) {
                continue;
            }
            foreach ($params as $eventName => $listener) {
                if ($name and $name != $eventName) {
                    continue;
                }
                //$methodName = 'bitrixEvent_' . $eventModule . '_' . $eventName;
                // Совместного использования с продолженным событиями
                $methodName = $eventModule . '_' . $eventName;
                foreach($listener as $listenerType => $listeners) {
                    switch ($listenerType) {
                        case 'invokables':
                            $events->registerEventsInvokables($listeners, $methodName);
                            break;
                        case 'factories':
                            $events->registerEventsFactories($listeners, $methodName);
                            break;
                        case 'services':
                            $events->registerEventsServices($listeners, $methodName);
                    }
                }
                // Удаляем описание зарегистрированных слушателей
                unset($this->bitrixEvents[$eventModule][$eventName]);
            }
        }
    }


    public static function __callStatic($name, $argumentsIn)
    {
        $eventManager = self::$serviceManager->get('event_manager');

        $arguments = $eventManager->prepareArgs($argumentsIn);

        $parts = explode('_', $name);
        if (isset($parts[0]) and isset($parts[1])) {
            self::$instance->registerListeners($parts[0], $parts[1]);
        }
        $res = $eventManager->trigger($name, self::$instance, $arguments);

        $arguments = $arguments->getArrayCopy();
        foreach($arguments as $key => $value) {
            $argumentsIn[$key] = $value;
        }

        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }
        return true;
    }

    /**
     *
     * Сдулатель события битроикса main OnBeforeLocalRedirect
     * Транслирует событие на конфигугрируемое main_OnBeforeLocalRedirect
     *
     * В слушателе дотупна параметры:
     * $params = $e->gtParams();
     * $params[0] - это строка-запрос, которую можно изменить
     * $params[1] - $skip_security_check - по умолчанию false
     *
     * В битриксе это запускается тут: bitrix/modules/main/tools.php (3191)
     * 		foreach(GetModuleEvents("main", "OnBeforeLocalRedirect", true) as $arEvent)
     *          ExecuteModuleEventEx($arEvent, array(&$url, $skip_security_check));
     *
     *
     * @param string $url ссылка, передается по ссылке - изменяемая
     * @param $skip_security_check
     * @return bool
     */
    public static function OnBeforeLocalRedirect(&$url, $skip_security_check)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs([$url, $skip_security_check]);

        $res = $eventManager->trigger('main_OnBeforeLocalRedirect', self::$instance, $arFields);
        $url = $arFields[0];
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }
    }

    /**
     * Вызывается в компоненте bitrix:sale.order.ajax после формирования списка доступных платежных систем,
     * может быть использовано для модификации данных.
     *
     * Чтобы изменить параметры нужно в обработчике сделать так:
     * В начале метода __invoke($e)
     *  $params = $e->getParams(); // Возвращает объект с интерфейсом массива
     *  $arResult = $params[0];
     *
     *  Манипуляции с $arResult
     *
     * В конце
     * $params[0] = $arResult;
     *
     * http://dev.1c-bitrix.ru/api_help/sale/events/events_components.php
     *
     * @param $arResult Массив arResult компонента
     * @param $arUserResult Массив arUserResult компонента, содержащий текущие выбранные пользовательские данные.
     * @param $arParams Массив параметров компонента
     * @return bool
     */
    public static function onSaleComponentOrderOneStepPaySystem(&$arResult, &$arUserResult, $arParams)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs([$arResult, $arUserResult, $arParams]);

        $res = $eventManager->trigger('sale_OnSaleComponentOrderOneStepPaySystem', self::$instance, $arFields);
        $arResult     = $arFields[0];
        $arUserResult = $arFields[1];
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }
    }

    public static function OnStartIBlockElementUpdate(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        $res = $eventManager->trigger('iblock_OnStartIBlockElementUpdate', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }

    }

    public static function OnStartIBlockElementAdd(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        $res = $eventManager->trigger('iblock_OnStartIBlockElementAdd', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }

    }


    public static function OnBeforeIBlockElementUpdate(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        $res = $eventManager->trigger('iblock_OnBeforeIBlockElementUpdate', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }

    }

    /**
     * Слушатель события, которое срабатывает здесь: bitrix/modules/main/interface/admin_lib.php (490)
     *
     * @param $aGlobalMenu
     * @param $aModuleMenu
     * @return bool
     */
    public static function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        $eventManager = self::$eventManager;
        $aGlobalMenu = $eventManager->prepareArgs($aGlobalMenu);
        $aModuleMenu = $eventManager->prepareArgs($aModuleMenu);

        $res = $eventManager->trigger('main_OnBuildGlobalMenu', self::$instance, [$aGlobalMenu, $aModuleMenu]);
        $aGlobalMenu = $aGlobalMenu->getArrayCopy();
        $aModuleMenu = $aModuleMenu->getArrayCopy();
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }

    }


    /**
     * Наложение на событие битрикса события системы citimall
     * Используется вместо bitrix_events для предоставления возможности изменять параметры.
     *
     * @param $arFields
     * @return bool
     */
    public static function OnBeforeIBlockElementAdd(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        // iblock.element.add.before -> iblock_OnBeforeIBlockElementAdd
        $res = $eventManager->trigger('iblock_OnBeforeIBlockElementAdd', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }

    }


    public static function onAfterUserLogin(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        // user.login.after -> main_OnAfterUserLogin
        $eventManager->trigger('main_OnAfterUserLogin', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();
    }

    /**
     * Почитать
     * http://dev.1c-bitrix.ru/api_help/main/events/onbeforeuserlogin.php
     *
     * @param $arFields
     * @return bool
     */
    public static function onBeforeUserLogin(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        /** @var \Zend\EventManager\ResponseCollection $res */
        // user.login.before -> main_OnBeforeUserLogin
        $res = $eventManager->trigger('main_OnBeforeUserLogin', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();
        if ($res->stopped()) {
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }

    }

    public static function onAfterUserRegister(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        // user.register.after -> main_OnAfterUserRegister
        $eventManager->trigger('main_OnAfterUserRegister', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();
    }

    /**
     * Почитать:
     * http://dev.1c-bitrix.ru/api_help/main/events/onbeforeuserregister.php
     *
     * @param $arFields
     * @return bool
     */
    public static function onBeforeUserRegister(&$arFields)
    {
        $eventManager = self::$eventManager;
        $arFields = $eventManager->prepareArgs($arFields);

        // user.register.before -> main_OnBeforeUserRegister
        $res = $eventManager->trigger('main_OnBeforeUserRegister', self::$instance, $arFields);
        $arFields = $arFields->getArrayCopy();

        if ($res->stopped()) {
            //  Прекращение метода CUser::Register
            $app = Registry::getApplication();
            $app->ThrowException($res->last());
            return false;
        }

    }

    /**
     * Внедрение сервис локатора
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        self::$serviceManager = $serviceLocator;
        self::$eventManager = $serviceLocator->get('event_manager');
    }

    /**
     * Возврат сервис локатора.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return self::$serviceManager;
    }

    public function __set($key, $value)
    {
        $this->sharedParams[$key] = $value;
    }

    public function __get($key)
    {
        if (isset($this->sharedParams[$key])) {
            return $this->sharedParams[$key];
        }
        return null;
    }

} 