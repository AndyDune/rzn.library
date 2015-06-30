<?
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */
namespace Rzn\Library\Component;
use Rzn\Library\ServiceManager\ServiceManager;
use Rzn\Library\Exception;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;

class HelperManager extends ServiceManager implements ServiceLocatorAwareInterface
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    protected $invokableClasses = array(
        'ajaxbehavior'                 => 'Rzn\Library\Component\Helper\AjaxBehavior',
        'arrayextractvalueswithprefix' => 'Rzn\Library\Component\Helper\ArrayExtractValuesWithPrefix',
        'drawimage'                    => 'Rzn\Library\Component\Helper\DrawImage',
        'drawmaxdimensionforimage'     => 'Rzn\Library\Component\Helper\DrawMaxDimensionForImage',
        'firstexistvalueinarray'       => 'Rzn\Library\Component\Helper\FirstExistValueInArray',
        'getiblockelementpropertyvaluewithcode' => 'Rzn\Library\Component\Helper\GetIblockElementPropertyValueWithCode',
        'getorderpropertywithcode'              => 'Rzn\Library\Component\Helper\GetOrderPropertyWithCode',
        'insertimages'         => 'Rzn\Library\Component\Helper\InsertImages',
        'pluralform'           => 'Rzn\Library\Component\Helper\PluralForm',
        'pr'                   => 'Rzn\Library\Component\Helper\Pr',
        'showerror'            => 'Rzn\Library\Component\Helper\ShowError',
        'shownote'             => 'Rzn\Library\Component\Helper\ShowNote',
        'stringwithwhitespace' => 'Rzn\Library\Component\Helper\StringWithWhiteSpace'
    );

    public function __construct()
    {

    }



    /**
     * Инициилизация сервисов из массива с конфигом.
     * Инициилизация этого сервоса из конфига происходит раньше внедрения в него менеджера сервисов.
     * Для этого передаем методу менеджер сервисов из фабрики.
     * todo протестировать возможногсть удаления этого метода.
     *
     * @return $this
     */
    public function initServicesFromConfig_toRemove(ServiceLocatorInterface $serviceLocator)
    {
        //echo '<p>initServicesFromConfig</p>';
        $config = $this->getConfig();
        if (isset($config['invokables'])) {
            foreach($config['invokables'] as $key => $value) {
                $this->setInvokableClass($key, $value);
            }
        }

        if (isset($config['factories'])) {
            foreach($config['factories'] as $key => $value) {
                $this->setFactory($key, $value);
            }
        }

        if (isset($config['aliases'])) {
            foreach($config['aliases'] as $key => $value) {
                $this->setAlias($key, $value);
            }
        }

        if (isset($config['initializers'])) {
            foreach($config['initializers'] as $key => $value) {
                $initializer = new $value();
                if ($initializer instanceof ServiceLocatorAwareInterface) {
                    $initializer->setServiceLocator($serviceLocator);
                }
                $this->addInitializer($initializer);
            }
        }
        return $this;
    }


    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
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


    public function __call($name, $params = null)
    {
        if ($this->has($name))
        {
            $helper = $this->get($name);
            if (is_callable($helper))
                return call_user_func_array($helper, $params);
            return $helper;
        }
        $serviceManager = $this->getServiceLocator();
        if (!$serviceManager->has($name))
            throw new Exception('Сервис с именем ' . $name . ' не зарегистрирован.');

        $helper = $serviceManager->get($name);
        if (is_callable($helper))
            return call_user_func_array($helper, $params);
        return $helper;

    }

    /**
     * Возвращает справочную информацию о зарегистрированных хелперах.
     * @return string
     */
    public function help()
    {
        ob_start();
        echo '<pre>';
        print_r($this->invokableClasses);
        echo '</pre>';
        $result = ob_get_clean();

        return $result;
    }
}