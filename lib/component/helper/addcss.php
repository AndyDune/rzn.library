<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 16.03.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Component\Helper;
use Rzn\Library\ServiceManager\ConfigServiceAwareInterface;
use Rzn\Library\ServiceManager\BitrixApplicationInterface;

class AddCss implements ConfigServiceAwareInterface, BitrixApplicationInterface
{

    /**
     * @var \Rzn\Library\Config
     */
    protected $config;

    /**
     * Массив из конфига add_css
     *
     * @var array
     */
    protected $descriptionArray = [];


    /**
     * @var \CMain
     */
    protected $application;

    protected $namesUsed = [];

    public function __invoke($group = '', $useApp = false)
    {
        $findItems = [];
        $findItemNames = [];
        foreach($this->descriptionArray as $key =>  $item) {
            if (in_array($key, $this->namesUsed)) {
                continue;
            }
            if ($group and (!isset($item['group']) or $item['group'] != $group)) {
                continue;
            }

            //если текущая url не совпадает с url в условиях(ограничениях - constraints) то это текущий add_css пропускается
            if (isset($item['constraints']['url']) and strpos($_SERVER['REQUEST_URI'], $item['constraints']['url']) !== 0) {
                continue;
            }

            //если текущая url совпадает с url в условиях(ограничениях - constraints) то это текущий add_css пропускается
            if (isset($item['constraints']['exept_url']) and strpos($_SERVER['REQUEST_URI'], $item['constraints']['exept_url']) === 0) {
                continue;
            }

            if (isset($item['constraints']['params']) and is_array($item['constraints']['params'])) {
                $find = false;
                foreach($item['constraints']['params'] as $paramName => $paramValues) {
                    if (!isset($_REQUEST[$paramName])) {
                        continue;
                    }

                    // Для единственного значения парамтера допустима строка
                    if (!is_array($paramValues)) {
                        $paramValues = [$paramValues];
                    }

                    foreach ($paramValues as $paramValue) {
                        if ('*' == $paramValue) {
                            $find = true;
                            /*
                             * Значение параметра * - включение файла при любом значении.
                             */
                            break;
                        }

                        if ($_REQUEST[$paramName] == $paramValue) {
                            $find = true;
                            /*
                             * Для текущего значения параметра было совпадение - прекращаем перебор параметров.
                             */
                            break;
                        }
                    }
                    /*
                     * Для текущего параметра было совпадение - прекращаем перебор параметров.
                     */
                    if ($find) {
                        break;
                    }
                }
                //
                if (!$find) {
                    continue;
                }
            }
            if (!isset($item['priority'])) {
                $item['priority'] = 0;
            }
            $findItemNames[] = $key;
            $findItems[$key] = $item;
        }

        if (!count($findItems)) {
            return '';
        }

        // Теперь сама сортировка
        usort($findItems, function ($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }
            return ($a['priority'] > $b['priority']) ? -1 : 1;
        });


        return $this->build($findItems, $useApp);
    }

    /**
     * Построение строки для вставки в html
     *
     * todo Встаивть использование хелпера file для вставки домена в ссылку
     * @param $array
     */
    public function build($array, $useApp = null)
    {
        //pr($array);
        ob_start();
        foreach($array as $item) {
            if (isset($item['file'])) {
                if ($useApp) {
                    $this->application->SetAdditionalCSS($item['file']);
                } else {
                    ?>
                    <link href="<?= $item['file'] ?>" rel="stylesheet"><?
                }
            }
        }
        return ob_get_clean();
    }

    /**
     * Инъекция сервиса конфига.
     *
     * @param \Rzn\Library\Config $service
     * @return mixed
     */
    public function setConfigService($service)
    {
        $this->config = $service;
        if ($service['add_css']) {
            $this->descriptionArray = $service['add_css']->toArray();
            }
    }

    /**
     * Возврат сервиса конфига.
     *
     * @return \Rzn\Library\Config
     */
    public function getConfigService()
    {
        return $this->config;
    }

    /**
     * @param \CMain $application
     * @return mixed
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @return \CMain
     */
    public function getApplication()
    {
        return $this->application;
    }
}