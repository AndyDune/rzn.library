<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 27.04.2016                                  |
 * ----------------------------------------------------
 *
 * Преобразование чисел в вид:
 *
 * 1.5000 -> 1.5
 * 1,5 -> 1.5
 * 1 ,a 5 -> 1.5
 * .5000 -> 0.5
 * 1.5000.30 -> 1.50003
 *
 */


namespace Rzn\Library\Component\Helper;


class DeleteZerosAfterDotInNumber
{
    public function __invoke($number)
    {
        $value = str_replace(',', '.', $number);
        $value = preg_replace('|[^.\d]|', '', $value);
        $value = preg_replace('|\.+|', '.', $value);
        $parts = explode('.', $value);
        if (count($parts) > 2) {
            $beforeDot = array_shift($parts);
            $afterDot = implode('', $parts);
            $parts = [$beforeDot, $afterDot];
        }

        $valueToReturn = (int)$parts[0];
        if (count($parts) == 2) {
            $afterDot  = (int)rtrim($parts[1], '0');
            if ($afterDot) {
                $valueToReturn .= '.' . $afterDot;
            }
        }

        return $valueToReturn;
    }
}