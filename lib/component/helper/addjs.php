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


class AddJs extends AddCss
{
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
                    $this->application->AddHeadScript($item['file']);
                } else {
                    ?><script type="text/javascript" src="<?= $item['file'] ?>"></script><?
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
        if ($service['add_js']) {
            $this->descriptionArray = $service['add_js']->toArray();
        }
    }

}