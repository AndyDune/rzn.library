<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 14.08.14                                      
  * ----------------------------------------------------
  *
  *
  * Обработчик ссылок перед вставкой их атрибут тега.
  *
  */


namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;


class PrintOuterLink extends HelperAbstract
{
    /**
     * Обработка внешней ссылки.
     *
     * @param $url
     * @return mixed
     */
    public function __invoke($url)
    {
        $parts = explode('://', $url);
        if (count($parts) > 1) {
            $url = $parts[1];
        }
        $url = 'http://' . $url;
        return $url;
    }

} 