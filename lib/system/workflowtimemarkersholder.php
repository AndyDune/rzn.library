<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 20.03.2016                                      
 * ----------------------------------------------------
*/
namespace Rzn\Library\System;

class WorkflowTimeMarkersHolder 
{
    protected $beginSeconds;

    protected $print = true;

    protected $timeMoments = [];

    public function __construct()
    {
        $this->beginSeconds = microtime(true);
    }

    public function __invoke($params, $config = [])
    {
        $time = microtime(true) - $this->beginSeconds;
        $this->timeMoments[$params] = number_format($time, 3, '.', ' ');
        if (isset($config['print'])) {
            if (!$this->print and !isset($config['set_print'])) {
                return '';
            }

            $pars = [];
            foreach($this->timeMoments as $key => $value) {
                $pars[] = $key . '=' . $value;
            }

            if ($config['print'] == 'string') {
                return implode(" ", $pars);
            }

            return "<div style=\"display: none;\">\r\n" . implode(" \r\n", $pars) . "\r\n</div>";
        }
        return $this->timeMoments;
    }

    public function setPrint($flag = true)
    {
        $this->print = $flag;
    }
}