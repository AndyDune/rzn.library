<?
global $MESS;
$strPath2Lang =  __DIR__;
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));


class rzn_library extends CModule
{
    public $MODULE_ID = "rzn.library";

    public function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = GetMessage("SCOM_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("SCOM_INSTALL_DESCRIPTION");
        $this->PARTNER_NAME = GetMessage("SPER_PARTNER");
        $this->PARTNER_URI = GetMessage("PARTNER_URI");

    }

    function DoInstall()
    {
        global $DB, $APPLICATION, $step;
        RegisterModule("rzn.library");
        $APPLICATION->IncludeAdminFile(GetMessage("FORM_INSTALL_TITLE"), __DIR__ . "/step1.php");
    }

    function DoUninstall()
    {
        global $DB, $APPLICATION, $step;
        UnRegisterModule("rzn.library");
        $APPLICATION->IncludeAdminFile(GetMessage("FORM_INSTALL_TITLE"), __DIR__ . "/unstep1.php");
    }
}
