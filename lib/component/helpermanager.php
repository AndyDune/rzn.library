<?
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */
namespace Rzn\Library\Component;
use Rzn\Library\ServiceManager;
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