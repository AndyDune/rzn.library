<?
namespace Rzn\Library\Component;
use Rzn\Library\Loader as Loader;
use Rzn\Library\Registry as Registry;
use Rzn\Library\Exception;
class Simple extends \CBitrixComponent
{


    /**
     * @var \CMain
     */
    protected $_application;


    /**
     * @var \CUser
     */
    protected $_user;

    /**
     * @var
     */
    protected $_db;


    protected $_stopFlag = false;


    protected $_executeResult = '';

    /**
     * В компонете есть кеширование.
     *
     * @var bool
     */
    protected $_isCache = true;


    /**
     * Включать ли отработку шаблона компонента.
     *
     * @var bool
     */
    protected $_includeComponentTemplate = true;



    /**
     * Список ключей из масива $this->arResult которые нужно включить в кеш.
     * @var array
     */
    protected $_arResultCacheKeys = array();


    /**
     * Если указано числовое значение, то это время кеширование.
     * Имеет приоритет над входным параметром $arParams["CACHE_TIME"]
     *
     * @var bool|int
     */
    protected $_cacheTime = false;


    /**
     * Страница в папке шаблонов для запуска.
     *
     * @var string
     */
    protected $_templatePage = '';

    /**
     * Модули, которые должны быть установлены в системе.
     * Иначе стоп.
     *
     * @var array
     */
    protected $_dependModules = array();


    public function executeComponent()
    {
        $registry = Registry::getInstance();

        $this->_application = $registry->getGlobal('APPLICATION');
        $this->_user = $registry->getUser();
        $this->_db = $registry->getGlobal('DB');

        try
        {

            $this->_initSpecial();
            if ($this->_stopFlag)
                return false;
            $this->_init();
            if ($this->_stopFlag)
                return false;

            $USER = $registry->getUser();

            $this->_executeFirst();

            if (!$this->_isCache)
            {
                $this->_execute();
                if ($this->_includeComponentTemplate)
                {
                    $helper = \Rzn\Library\Registry::getServiceManager()->get('helper_manager');
                    $this->arResult['helper_object'] = $this->arResult['helper'] = $helper;
                    $this->includeComponentTemplate($this->_templatePage);
                }
                goto next;
            }

            $cacheKeys = array('common' => $this->_getCacheKey());
            if ($this->arParams["CACHE_GROUPS"]==="Y")
                $cacheKeys['groups'] = $USER->GetGroups();

            if($this->StartResultCache($this->_cacheTime,
                $cacheKeys)
            )
            {
                $this->_execute();
                $result = $this->_getResultCacheKeys();
                if(is_array($result))
                    $this->_arResultCacheKeys = $result;
                $this->SetResultCacheKeys($this->_arResultCacheKeys);
                if ($this->_includeComponentTemplate)
                {
                    $this->arResult['helper_object'] = $this->arResult['helper'] = HelperManager::getInstance();
                    $this->includeComponentTemplate($this->_templatePage);
                }
                else
                    $this->EndResultCache();
            }

            next:
            $this->_executeAfterCache();

        }
        catch (Exception $e)
        {
            if ($this->_isCache)
                $this->AbortResultCache();
            return false;
        }
        return $this->_prepareReturnData();
    }

    protected  function setTemplatePage($name = '')
    {
        $this->_templatePage = $name;
        return $this;
    }

    /**
     * Метод для перегрузки.
     * Отрабатывается код до начала включения кешировангия.
     * До метода _execute()
     *
     */
    protected function _executeFirst()
    {

    }


    final function getRequestArrayContainer($default = null)
    {
        return new \Rzn\Library\ArrayContainer($_REQUEST, $default);
    }

    final function getGetArrayContainer($default = null)
    {
        return new \Rzn\Library\ArrayContainer($_GET, $default);
    }

    final function getPostArrayContainer($default = null)
    {
        return new \Rzn\Library\ArrayContainer($_POST, $default);
    }


    final protected function _initSpecial()
    {
        $modules = $this->_getDependModules();
        if ($modules and is_array($modules))
        {
            foreach ($modules as $module)
            {
                if (!\CModule::IncludeModule($module))
                {
                    $message = 'Не загружен модуль: ' . $module;
                    ShowError(GetMessage($message));
                    $this->_stopProcess();
                    return false;
                }
            }
        }
        $arParams = $this->arParams;



        $params = $this->_getParamsRequired();
        if ($params)
        {
            foreach($params as $param)
            {
                if (!isset($arParams[$param]))
                {
                    $message = 'Не установлен обязательный параметр: ' . $param;
                    ShowError(GetMessage($message));
                    $this->_stopProcess();
                    return false;
                }
            }
        }

        $params = $this->_getParamsTrim();
        if ($params)
        {
            if (!is_array($params))
                $params = array($params);
            foreach($params as $param)
            {
                if (!isset($arParams[$param]))
                    $arParams[$param] = '';
                else
                    $arParams[$param] = trim($arParams[$param]);
            }

        }
        $params = $this->_getParamsDefaults();
        if ($params and is_array($params))
        {
            foreach($params as $param => $value)
            {
                if (!isset($arParams[$param]))
                    $arParams[$param] = $value;
            }
        }

        $params = $this->_getParamsArray();
        if ($params)
        {
            if (!is_array($params))
                $params = array($params);
            foreach($params as $param => $value)
            {
                if ((int)$param != $param)
                {
                    if (!isset($arParams[$param]) || !is_array($arParams[$param]) || count($arParams[$param]) <= 0)
                    {
                        $arParams[$param] = $value;
                    }
                }
                else
                    $param = $value;
                if (!isset($arParams[$param]))
                    $arParams[$param] = array();
                else if (!is_array($arParams[$param]))
                    $arParams[$param] = array($arParams[$param]);
            }

        }

        $params = $this->_getParamsBoolean();
        if ($params and is_array($params))
        {
            foreach($params as $param => $value)
            {
                if (!isset($arParams[$param]) or !in_array($param, array('Y', 'N')))
                    $arParams[$param] = $value;
            }
        }

        $this->arParams = $arParams;
    }


    /**
     * Остановка работы клмпонента и выход.
     *
     * @return $this
     */
    protected function _stopProcess()
    {
        $this->_stop = true;
        return $this;
    }


    /**
     * Возвращает ключи для кеширования.
     * Ключи являются добавочными к стандартным для компонента.
     * Примеры:
     * return array($bUSER_HAVE_ACCESS,
                    $arNavigation,
                    $arrFilter)
     *
     * @return array массив ключей
     */
    protected function _getCacheKey()
    {
        $result = false;
        return $result;
    }


    /**
     * Возврат масива ключей массива $this->arResult которые должны попасть в кеш.
     *
     * @return null/array
     */
    protected function _getResultCacheKeys()
    {
        return null;
    }


    /**
     * Возвращает массив с модулями, от которых зависит.
     * Прегрузить в дочернем классе по необходимости.
     *
     * @return null|array
     */
    protected function _getDependModules()
    {
        return null;
    }


    /**
     * Подготовка перед работой основного метода.
     *
     */
    protected function _init()
    {
    }


    /**
     * Этот метод перегружать кодом, который может кешироваться.
     * Перед отрисовкой шаблона.
     */
    protected function _execute()
    {

    }

    /**
     * Блок кода, который выполняется после отрисовки шаблона.
     * Не кешируется.
     *
     */
    protected function _executeAfterCache()
    {

    }

    /**
     * Подготовка данных, которые возвращает компонент.
     *
     * @return null|array
     */
    protected function _prepareReturnData()
    {
        return null;
    }


    /**
     * Возврат массива параметров, которые нужно подвергнуть trim.
     * Если такого параметра нет, он по умолчанию проиобретает значение "" (пустая строка)
     *
     * @return null|array
     */
    protected function _getParamsTrim()
    {
        return null;
    }

    /**
     * Возврат массива значений по умолчанию для входных параметров.
     *
     * array(
     *        <параметр> => <значение по умолчанию>,
     *        ...
     *      )
     *
     * @return null|array
     */
    protected function _getParamsDefaults()
    {
        return null;
    }

    /**
     * Возврат массива параметров, которые должны быть массивами.
     * Если значение не массив, тогда оно становится первым в массиве.
     * Если задан ассоциативный массив наряду с числовым, тогда параметр должен быть ненулевым массивом.
     * Если он таковым не является - присваивается значение за текущим ключем.
     *
     * @return null|array
     */
    protected function _getParamsArray()
    {
        return null;
    }

    /**
     * Возвращает ключи с будевыми тпами. Значения - умолчания.
     *
     * @return null|array
     */
    protected function _getParamsBoolean()
    {
        return null;
    }


    /**
     * Возвращает массив с обязательными параметрами
     *
     * @return null|array
     */
    protected function _getParamsRequired()
    {
        return null;
    }


    public function pr($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}