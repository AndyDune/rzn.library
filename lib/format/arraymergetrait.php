<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 16.12.2015                                     |
 * ----------------------------------------------------
 *
 */


namespace Rzn\Library\Format;


trait ArrayMergeTrait
{
    public function arrayMerge($array1, $array2)
    {
        if (!$array2 or !is_array($array2)) {
            return $array1;
        }
        if (!$array1) {
            $array1 = [];
        }
        foreach($array2 as $key => $value) {
            if (is_int($key)) {
                $array1[] = $value;
                continue;
            }
            $array1[$key] = $value;
        }
        return $array1;
    }
}