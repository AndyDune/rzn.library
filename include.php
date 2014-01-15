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

spl_autoload_register(array('Rzn\Library\Loader', 'autoload'));

IncludeModuleLangFile(__FILE__);

use Rzn\Library\Registry;
use Rzn\Library\Session;

$sm = Registry::getServiceManager();

$sm->setInitializer(new \Rzn\Library\ServiceManager\InterfaceInitializer($sm));

$sm->setFactory('session', function($m){
    $object = Session::getInstance();
    return $object;
});

//$sm->setInvokableClass('string_explode', 'Rzn\Library\String\Explode');
$sm->setInvokableClass('plugin_manager', 'Rzn\Library\Component\HelperManager');
$sm->setAlias('helper_manager', 'plugin_manager');
$sm->setInvokableClass('test_data', 'Rzn\Library\Directory\AsArray');
