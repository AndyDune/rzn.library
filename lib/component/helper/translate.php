<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 08.11.14                                      
  * ----------------------------------------------------
  *
  * Общая локализация для всего шаблона в одном месте.
  *
  */


namespace Rzn\Library\Component\Helper;

use Rzn\Library\Component\HelperAbstract;
use Rzn\Library\ServiceManager\InvokeInterface;

class Translate extends HelperAbstract implements InvokeInterface
{
    protected $texts = [];

    protected $space = 'main';

    public function __invoke($string, $space = '')
    {
        if (!$space) {
            $space = $this->space;
        }

        if (!isset($this->texts[$space])) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/local/templates/' . SITE_TEMPLATE_ID . '/translate/ru/' . $space . '.php';
            if (is_file($path)) {
                $this->texts[$space] = include($path);
            } else {
                $this->texts[$space] = [];
            }
        }
        if (isset($this->texts[$space][$string])) {
            return $this->texts[$space][$string];
        }
        return $string;
    }

    public function invoke($serviceManager)
    {
        $this->space = 'main';
    }

    public function useSpace($space)
    {
        $this->space = $space;
    }

} 