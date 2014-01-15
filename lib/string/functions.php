<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 04.10.13
 * Time: 14:54
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\String;


class Functions
{
    static function strToLower($str)
    {
        return mb_strtolower($str, 'UTF-8');
    }
}