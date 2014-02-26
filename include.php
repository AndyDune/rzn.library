<?
CModule::AddAutoloadClasses(
    "rzn.library",
    array(
        'Rzn\Library\Loader' => 'lib/loader.php',
        'Rzn\Library\Registry' => 'lib/registry.php',
        //'Rzn\\Library\\Component\\Complex' => 'lib/component/complex.php',
        //'Rzn\\Library\\Component\\Simple' => 'lib/component/simple.php',
        //'Rzn\\Library\\Component\\HelperManager' => 'lib/component/helpermanager.php',
        //'Rzn\\Library\\Component\\HelperAbstract' => 'lib/component/helperabstract.php',
        //'Rzn\\Library\\ArrayContainer' => 'lib/arraycontainer.php',
    )
);

//// Автозагрузка для версии битрикса до 14
spl_autoload_register(array('Rzn\Library\Loader', 'autoload'));

IncludeModuleLangFile(__FILE__);

use Rzn\Library\Registry;

$sm = Registry::getServiceManager();

$sm->addInitializer(new \Rzn\Library\ServiceManager\InterfaceInitializer($sm));

$serviceLazyConfig = new \Rzn\Library\ServiceManager\LazyConfig($sm);

$sm->addInitializer($serviceLazyConfig);
$sm->setService('service_lazy_config', $serviceLazyConfig);

/*
 * Теперь без синглетона
$sm->setFactory('session', function($m){
    $object = Session::getInstance();
    return $object;
});
*/
$sm->setInvokableClass('session', 'Rzn\Library\Session');
$sm->setInvokableClass('plugin_manager', 'Rzn\Library\Component\HelperManager');
$sm->setInvokableClass('test_data', 'Rzn\Library\Directory\AsArray');

$sm->get('plugin_manager')->addInitializer(new \Rzn\Library\ServiceManager\InterfaceInitializer($sm));

$sm->setAlias('helper_manager', 'plugin_manager');
