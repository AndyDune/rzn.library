<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 27.05.13
 * Time: 15:19
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\BitrixTrial\User;


class GeneratePassword
{
    public function __construct()
    {

    }

    public function get()
    {
        $USER = \Rzn\Library\Registry::getUser();
        $def_group = \COption::GetOptionString("main", "new_user_registration_def_group", "");
        if($def_group!="")
        {
            $GROUP_ID = explode(",", $def_group);
            $arPolicy = $USER->GetGroupPolicy($GROUP_ID);
        }
        else
        {
            $arPolicy = $USER->GetGroupPolicy(array());
        }

        $password_min_length = intval($arPolicy["PASSWORD_LENGTH"]);
        if($password_min_length <= 0)
            $password_min_length = 6;
        $password_chars = array(
            "abcdefghijklnmopqrstuvwxyz",
            "ABCDEFGHIJKLNMOPQRSTUVWXYZ",
            "0123456789",
        );
        if($arPolicy["PASSWORD_PUNCTUATION"] === "Y")
            $password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";

        return randString($password_min_length+2, $password_chars);
    }
}