<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */

namespace Rzn\Library;
use COption;

class Request
{
    protected $subDomainHolder = 'host';

    protected $subDomainQueryParameterName = 'subdomain';

    protected $subDomainForShop = null;


    protected $domain = 'citimall.ru';

    protected $subDomain = '';

    protected $params = [];


    public function __construct()
    {
        $parts = explode('.', $_SERVER['HTTP_HOST']);
        $countParts = count($parts);

        if ($countParts > 1) {
            $this->domain = $parts[$countParts - 2] . '.' . $parts[$countParts - 1];
            if (isset($parts[$countParts - 3])) {
                $this->subDomain = $parts[$countParts - 3];
            }
        }
    }

    public function getParam($param, $default = null)
    {
        if (isset($this->params[$param])) {
            return $this->params[$param];
        }
        return $default;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function addParam($param, $value)
    {
        $this->params[$param] = $value;
        return $this;
    }

    /**
     * Является ли запрос аяксовым.
     * @return bool
     */
    public function isAjax()
    {
        /**
         * Проверяем сначала в Битрикс стиле.
         */
        if ($_REQUEST["AJAX_CALL"] == "Y" || $_REQUEST["is_ajax_post"] == "Y") {
            return true;
        }
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public function isPost()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            return true;
        }
        return false;
    }

    public function getSubDomainForShop()
    {
        if ($this->subDomainForShop !== null) {
            return $this->subDomainForShop;
        }
        $subDomain = $this->_extractSubDomain();

        if ($subDomain and !in_array($subDomain, array('www', 'test'))) {
            $this->subDomainForShop = $subDomain;
        } else {
            $this->subDomainForShop = false;
        }

        return $this->subDomainForShop;
    }

    /**
     * Возвращает корневой домен
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }


    protected function _extractSubDomain()
    {
        // Режим query может работать всегда по продолжению метода.
        $subDomain = $this->subDomainQueryParameterName;
        if (isset($_GET[$subDomain]) and $_GET[$subDomain]) {
            $subDomain = $_GET[$subDomain];
            $this->subDomainHolder = 'query';
        } else if (0 and isset($_POST[$subDomain]) and $_POST[$subDomain]) { // В post не ищем
            $subDomain = $_POST[$subDomain];
            $this->subDomainHolder = 'query';
        } else {
            $subDomain = '';
        }


        switch ($this->subDomainHolder) {
            case 'host':
                $subDomain = $this->subDomain;
                break;
            case 'query':
                // Отключение кеша, если работаем в query режиме
                COption::SetOptionString("main", "component_cache_on", "N");
        }
        return $subDomain;
    }

    /**
     *
     *
     * @param string $type Варианты: host|query
     * @return $this
     */
    public function setSubDomainHolder($type = 'host')
    {
        $this->subDomainHolder = $type;
        return $this;
    }

    public function getSubDomainHolder()
    {
        return $this->subDomainHolder;
    }

    public function getSubDomainQueryParameterName()
    {
        return $this->subDomainQueryParameterName;
    }


    /**
     * Возвращает все post параметры или только один указанный.
     *
     * @param string $param Название параметра или null если нужно выбрать все.
     * @param mixed $default Значение по-умолчанию
     * @return mixed
     */
    public function fromPost($param = null, $default = null)
    {
        if ($param === null) {
            return $_POST;
        }
        if (isset($_POST[$param])) {
            return $_POST[$param];
        }
        return $default;
    }

    /**
     * Возвращает все параметры из строки запроса или только один указанный.
     *
     * @param string $param Название параметра или null если нужно выбрать все.
     * @param mixed $default Значение по-умолчанию
     * @return mixed
     */
    public function fromQuery($param = null, $default = null)
    {
        if ($param === null) {
            return $_GET;
        }
        if (isset($_GET[$param])) {
            return $_GET[$param];
        }
        return $default;
    }

} 