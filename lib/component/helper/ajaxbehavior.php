<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 20.05.13
 * Time: 17:28
 *
 * Для более удобной работы при организации особенного поведения при аякс запросах.
 * Предполагается, что один и тот же код будет работать как с обычными запросами так и с аяксом.
 *
 */

namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;
use Rzn\Library\ArrayContainer;
use Rzn\Library\Registry;

class AjaxBehavior extends HelperAbstract
{
    public function __invoke()
    {
        return $this;
    }

    public function isAjax()
    {
        $array = new ArrayContainer($_REQUEST);
        if($array["AJAX_CALL"] == "Y" or $array["is_ajax_post"] == "Y" or
            (   isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                and
                $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
            )
          )
        {
            return true;
        }
        return false;
    }

    public function includeHeader()
    {
        if ($this->isAjax())
        {
            //require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
        }
        else
        {
            $APPLICATION = Registry::getGlobal('APPLICATION');
            $USER = Registry::getUser();
            require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");
        }
        return $this;

    }

    public function includeFooter()
    {
        if ($this->isAjax())
        {
            //require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
        }
        else
        {
            $APPLICATION = Registry::getGlobal('APPLICATION');
            $USER = Registry::getUser();
            require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_before.php");
        }

        require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_after.php");

        return $this;
    }


}