<?
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */

//autoload psr-0
spl_autoload_register(function ($className) {
    $includeNamespaces = [
        'Zend' => __DIR__ . '/vendor/zendframework/library',
        'Rzn' => __DIR__ . '/lib'
    ];
    //$includePath = __DIR__ . '/vendor/zendframework/library';
    foreach ($includeNamespaces as $includeNamespace => $includePath) {
        if ($includeNamespace . '\\' === substr($className, 0, strlen($includeNamespace . '\\'))) {
            $fileName = '';
            if (false !== ($lastNsPos = strripos($className, '\\'))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

            $fileName = ($includePath !== null ? $includePath . DIRECTORY_SEPARATOR : '') . $fileName;
            if (is_readable($fileName)) {
                require $fileName;
            }
        }
    }
});


//$zendPrefix = '';
use Bitrix\Main\Loader;

CModule::AddAutoloadClasses(
    "rzn.library",
    array(
        'Rzn\Library\Loader' => 'lib/loader.php',
        'Rzn\Library\Registry' => 'lib/registry.php',

                'Zend\Stdlib\MessageInterface'    => 'vendor/zendframework/library/Zend/Stdlib/MessageInterface.php',
                'Zend\Stdlib\Message'             => 'vendor/zendframework/library/Zend/Stdlib/Message.php',
                'Zend\Stdlib\Parameters'          => 'vendor/zendframework/library/Zend/Stdlib/Parameters.php',
                'Zend\Stdlib\ParametersInterface' => 'vendor/zendframework/library/Zend/Stdlib/ParametersInterface.php',
                'Zend\Stdlib\RequestInterface'    => 'vendor/zendframework/library/Zend/Stdlib/RequestInterface.php',
                'Zend\Stdlib\CallbackHandler'     => 'vendor/zendframework/library/Zend/Stdlib/CallbackHandler.php',
                'Zend\Stdlib\PriorityQueue'       => 'vendor/zendframework/library/Zend/Stdlib/PriorityQueue.php',
                'Zend\Stdlib\SplPriorityQueue'    => 'vendor/zendframework/library/Zend/Stdlib/SplPriorityQueue.php',
                'Zend\Stdlib\ReflectionClass'     => 'vendor/zendframework/library/Zend/Stdlib/ReflectionClass.php',


                'Zend\Uri\Exception' => 'vendor/zendframework/library/Zend/Uri/Exception.php',
                'Zend\Uri\Http' => 'vendor/zendframework/library/Zend/Uri/Http.php',

                'Zend\Http\Request' => 'vendor/zendframework/library/Zend/Http/Request.php',
                'Zend\Http\AbstractMessage' => 'vendor/zendframework/library/Zend/Http/AbstractMessage.php',
                'Zend\Http\Exception\ExceptionInterface' => 'vendor/zendframework/library/Zend/Http/Exception/ExceptionInterface.php',
                'Zend\Http\Exception\InvalidArgumentException' => 'vendor/zendframework/library/Zend/Http/Exception/InvalidArgumentException.php',
                'Zend\Http\Exception\OutOfRangeException' => 'vendor/zendframework/library/Zend/Http/Exception/OutOfRangeException.php',
                'Zend\Http\Exception\RuntimeException' => 'vendor/zendframework/library/Zend/Http/Exception/RuntimeException.php',


                'Zend\EventManager\Exception\InvalidArgumentException' => 'vendor/zendframework/library/Zend/EventManager/Exception/InvalidArgumentException.php',
                'Zend\EventManager\Exception\InvalidCallbackException' => 'vendor/zendframework/library/Zend/EventManager/Exception/InvalidCallbackException.php',
                'Zend\EventManager\Exception\DomainException'          => 'vendor/zendframework/library/Zend/EventManager/Exception/DomainException.php',
                'Zend\EventManager\Exception\ExceptionInterface'       => 'vendor/zendframework/library/Zend/EventManager/Exception/ExceptionInterface.php',

                'Zend\EventManager\EventManagerInterface' => 'vendor/zendframework/library/Zend/EventManager/EventManagerInterface.php',
                'Zend\EventManager\EventInterface'        => 'vendor/zendframework/library/Zend/EventManager/EventInterface.php',
                'Zend\EventManager\EventManager'          => 'vendor/zendframework/library/Zend/EventManager/EventManager.php',

                'Zend\EventManager\Event'   => 'vendor/zendframework/library/Zend/EventManager/Event.php',

                'Zend\EventManager\EventManagerAwareInterface'   => 'vendor/zendframework/library/Zend/EventManager/EventManagerAwareInterface.php',
                'Zend\EventManager\EventManagerAwareTrait'       => 'vendor/zendframework/library/Zend/EventManager/EventManagerAwareTrait.php',
                'Zend\EventManager\EventsCapableInterface'       => 'vendor/zendframework/library/Zend/EventManager/EventsCapableInterface.php',
                'Zend\EventManager\FilterChain'                  => 'vendor/zendframework/library/Zend/EventManager/FilterChain.php',
                'Zend\EventManager\GlobalEventManager'           => 'vendor/zendframework/library/Zend/EventManager/GlobalEventManager.php',
                'Zend\EventManager\ListenerAggregateInterface'   => 'vendor/zendframework/library/Zend/EventManager/ListenerAggregateInterface.php',
                'Zend\EventManager\ListenerAggregateTrait'       => 'vendor/zendframework/library/Zend/EventManager/ListenerAggregateTrait.php',
                'Zend\EventManager\ResponseCollection'           => 'vendor/zendframework/library/Zend/EventManager/ResponseCollection.php',
                'Zend\EventManager\SharedEventAggregateAwareInterface'   => 'vendor/zendframework/library/Zend/EventManager/SharedEventAggregateAwareInterface.php',
                'Zend\EventManager\SharedEventManagerInterface'          => 'vendor/zendframework/library/Zend/EventManager/SharedEventManagerInterface.php',
                'Zend\EventManager\SharedEventManager'                   => 'vendor/zendframework/library/Zend/EventManager/SharedEventManager.php',
                'Zend\EventManager\SharedEventManagerAwareInterface'     => 'vendor/zendframework/library/Zend/EventManager/SharedEventManagerAwareInterface.php',
                'Zend\EventManager\SharedListenerAggregateInterface'     => 'vendor/zendframework/library/Zend/EventManager/SharedListenerAggregateInterface.php',
                'Zend\EventManager\StaticEventManager'                   => 'vendor/zendframework/library/Zend/EventManager/StaticEventManager.php',

        //        'Zend\EventManager\'   => 'vendor/zendframework/library/Zend/EventManager/.php',
                'Zend\EventManager\Filter\FilterInterface'  => 'vendor/zendframework/library/Zend/EventManager/Filter/FilterInterface.php',
                'Zend\EventManager\Filter\FilterIterator'   => 'vendor/zendframework/library/Zend/EventManager/FilterIterator.php',

    )
);

IncludeModuleLangFile(__FILE__);

function rzn_getenv($varname, $default = false) {
    $value = getenv($varname);
    if ($value === false) {
        return $default;
    }
    return $value;
}

use Rzn\Library\Registry;

$sm = Registry::getServiceManager();

// Сам у себя менеджер сервисов
$sm->setServiceLocator($sm);

$sm->setAllowOverride(true);

$fileAllowCache = __DIR__ . '/../../config/cache.php';
if (file_exists($fileAllowCache)) {
    $cacheAllow = true;
} else {
    $cacheAllow = false;
}

$arInstalledModules = Bitrix\Main\ModuleManager::getInstalledModules();

$cacheObjectCreate = function() use ($arInstalledModules) {
    $config = new Rzn\Library\Config([]);
    $config->addModule('rzn.library');
    $config->addApplication();

    if ($config['modules'] and count($config['modules'])) {
        foreach ($config['modules'] as $module) {
            if (isset($arInstalledModules[$module])) {
                $config->addModule($module);
            }
        }
    }
    return $config;
};

if ($cacheAllow) {
    $cache = Bitrix\Main\Data\Cache::createInstance();
    $cacheId = 'rzn_config';
    $config = null;

    if ($cache->startDataCache(360000, $cacheId, $cacheId)) {
        $config = $cacheObjectCreate();
        $cache->endDataCache(array("config" => $config));
    } else {
        $res = $cache->getVars();
        if ($res and isset($res['config'])) {
            $config = $res['config'];
        }
    }

    // На всякий случай
    if (!$config) {
        $config = $cacheObjectCreate();
    }
} else {
    $config = $cacheObjectCreate();
}

if ($config['modules'] and count($config['modules'])) {
    foreach ($config['modules'] as $module) {
        Loader::includeModule($module);
    }
}

$sm->setService('config', $config);

if ($config['add_config'] and count($config['add_config'])) {

    foreach ($config['add_config'] as $con) {
        if (!isset($con['file'])) {//если в текущем add_config нет file_array - значит add_config ошибочный и его пропускаем
            continue;
        }

        //если текущая url не совпадает с url в условиях(ограничениях - constraints) то это текущий add_config пропускается
        if (isset($con['constraints']['url']) and strpos($_SERVER['REQUEST_URI'], $con['constraints']['url']) !== 0) {
            continue;
        }
        $config->addConfig(include($con['file']));
    }
}

//$fileInitLocal = __DIR__ . '/config/local.config.php';
$fileInitLocal = $_SERVER['DOCUMENT_ROOT'] . '/local/config/local.config.php';
if (is_file($fileInitLocal)) {
    /**
     * Подключение локального файла настроек.
     * Сюда здесь вносить исправления в набор сервисов последующиъ 2-х модулей.
     *
     */
    $config->addConfig(include($fileInitLocal));
}

$fileInitLocal = $_SERVER['DOCUMENT_ROOT'] . '/local/config/evn.config.php';
if (is_file($fileInitLocal)) {
    $config->addConfig(include($fileInitLocal));
}


$sm->setConfig($config['service_manager']);

$sm->initServicesFromConfig();


$fileInitLocal = $_SERVER['DOCUMENT_ROOT'] . '/local/init.local.php';
if (is_file($fileInitLocal)) {
    include($fileInitLocal);
}

$parts = explode('.', $_SERVER['HTTP_HOST']);
$countParts = count($parts);

if ($countParts > 1) {
    Registry::set('HTTP_HOST_BASE', $parts[$countParts - 2] . '.' . $parts[$countParts - 1]);
} else
    Registry::set('HTTP_HOST_BASE', $config['main']['domain']);


if (defined('RZN_LIBRARY_NO_EVENTS_ATTACHE')) {
    return;
}
$events = $sm->get('event_manager');
/** @var Zend\EventManager\ResponseCollection $res */
$res = $events->trigger('init.post', null);
if ($res->stopped()) {
    die();
}
