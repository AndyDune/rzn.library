<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 21.07.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Component\Helper;

use Rzn\Library\Component\HelperAbstract;
use Rzn\Library\ServiceManager\ServiceLocatorAwareInterface;
use Rzn\Library\ServiceManager\ServiceLocatorInterface;


class Url extends HelperAbstract implements ServiceLocatorAwareInterface
{

    protected $serviceManager;

    protected $queryModifiers = [];

    /** @var  \Rzn\Library\Request */
    protected $request;

    /**
     * Текущий домен.
     * @var null
     */
    protected $domain = null;

    protected $currentSubDomain = '';


    protected $goToRootSite = false;


    protected $config = [];

    public function __invoke($url, $subDomain = null, $params = [], $config = [])
    {
        $this->goToRootSite = false;
        if ($subDomain) {
            if ($subDomain == 'www') { // Ссылка на рут сайт.
                $this->goToRootSite = true;
                $subDomain = null;
            }
        }
        if (isset($config['use_current'])) {
            $params = array_merge($params, $_GET);
        }

        // Допустима передача произвольтного url для использовании в строительстве.
        if (isset($config['url'])) {
            $delimiterForParams = '&';
            $urlParts = parse_url($config['url']);

            // Есть GET строка
            if ($urlParts['query']) {
                $paramsList = explode($delimiterForParams, $urlParts['query']);
                $paramsWasInUrl = [];
                // Извлекаем параметры из GET строки
                array_walk($paramsList, function ($value, $key) use (&$paramsWasInUrl) {
                    $parts = explode('=', $value);
                    if (isset($parts[1])) {
                        $paramsWasInUrl[$parts[0]] = $parts[1];
                    }
                });
                // Наклыдываем на набор текущий параметров (ежели был указан use_current)
                // полученные из GET строки параметры
                $params = array_merge($params, $paramsWasInUrl);
            }
        }


        if (isset($config['delete_params'])) {
            // При задании единственного
            if (!is_array($config['delete_params'])) {
                $config['delete_params'] = [$config['delete_params']];
            }

            foreach ($config['delete_params'] as $run) {
                unset($params[$run]);
            }

        }
        $this->config = $config;
        $url = $this->_buildUrl($url, $subDomain, $params);
        return $url;
    }

    /**
     * Используется изменяторах запроса.
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Внедрение сервис локатора.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->request = $serviceLocator->get('request');
        $this->currentSubDomain = $this->request->getSubDomainForShop();
        $this->serviceManager = $serviceLocator;
        return $this;
    }

    /**
     * Зарегистрировать изменятора списка параметров GET запроса.
     * Для удачного запуска изменятор должен быть звмыканием или реализовать метод __invoke
     * Параметры для запускаемой анонимной функции или метода:
     * 1. Список параметров
     * 2. Ссылка - домен плюс путь
     * 3. context - текущий сервис
     *
     * Возаращеть изменятор должен результирующмй массив параметров, который заменит массив параметров.
     *
     * @param $queryModifier
     * @return $this
     */
    public function addQueryModifier($queryModifier)
    {
        $this->queryModifiers[] = $queryModifier;
        return $this;
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

    public function getDomain()
    {
        if ($this->domain === null) {
            $this->domain = $this->request->getDomain();
        }
        return $this->domain;
    }


    protected function _buildUrl($url, $code, $params = [])
    {
        $delimiterForParams = '&';
        $urlParts = parse_url($url);
        $url = $urlParts['path'];
        $url = '/' . ltrim($url, '/');

        if ($urlParts['query']) {
            $paramsList = explode($delimiterForParams, $urlParts['query']);
            $paramsWasInUrl = [];
            array_walk($paramsList, function($value, $key) use(&$paramsWasInUrl) {
                $parts = explode('=', $value);
                if (isset($parts[1])) {
                    $paramsWasInUrl[$parts[0]] = $parts[1];
                }
            });
            $params = array_merge($paramsWasInUrl, $params);
        }


        if ($this->request->getSubDomainHolder() == 'query') {
            $subDomainParamName = $this->request->getSubDomainQueryParameterName();

            if ($code) { // Указан субдомен
                $params[$subDomainParamName] = $code;
            } else  if ($this->goToRootSite) {
                // По сути это отправка на рут сайт
                unset($subDomainParamName);
            } else if (!$this->goToRootSite and $this->currentSubDomain) { // Оставеся где были
                // Не указан ни субдомен ни рут-сайт
                $params[$subDomainParamName] = $this->currentSubDomain;
            } else {
                // По сути это отправка на рут сайт
                unset($subDomainParamName);
            }
        } else {
            $domain = $this->getDomain();
            if ($code) {
                $url = 'http://' . $code . '.' . $domain . $url;
            } else  if ($this->goToRootSite) {
                $url = 'http://' . $domain . $url;
            } else if (!$this->goToRootSite and $this->currentSubDomain) {
                $url = 'http://' . $this->currentSubDomain . '.' . $domain . $url;
            }
        }


        // Запускаем изменятора списка параметров
        if (count($this->queryModifiers)) {
            foreach($this->queryModifiers as $modifier) {
                if (is_callable($modifier)) {
                    $params = $modifier($params, $url, $this);
                }
            }
        }
        if (count($params)) {
            $line = [];
            array_walk($params, function($value, $key) use (&$line) {
                $line[] = $key . '=' . $value;
            });
            $url .= '?' . implode($delimiterForParams, $line);
        }
        if ($urlParts['fragment']) {
            $url .=  '#' . $urlParts['fragment'];
        }


        return $url;
    }


} 