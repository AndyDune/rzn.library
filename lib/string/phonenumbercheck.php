<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */

namespace Rzn\Library\String;
use Rzn\Library\Exception;

class PhoneNumberCheck
{
    protected $_number = '';
    protected $_numberResult = '';

    public function __construct($phone)
    {
        if (strlen($phone < 10))
            throw new Exception('Номер телефона не полный: ' . $phone, 1);
        $this->_number   = $phone;
    }

    public function getNumberWithoutCountryCode()
    {
        $len = strlen($this->_number);
        if ($len <= 10)
            return $this->_number;
        return substr($this->_number, $len - 10, 10);
    }

    /**
     * Не работает пока.
     * @return bool
     */
    public function isMobile()
    {
        return true;
    }

    public function isRussian()
    {
        $phone = '7' . $this->getNumberWithoutCountryCode();
        if ( substr($phone,0,2) == '79' && substr($phone,0,4) != '7940' )
            return true;

        return false;

    }

}