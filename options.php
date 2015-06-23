<?php
/** @file
 *
 *
 * Подготовка настроек для модуля(библиотеки) в админпанели битрикса
 *
 * <strong>Этот файл автоматически подключается битриксом</strong>
 *
 * Настройки -> Настройки продукта -> Настройки модулей -> <Имя модуля>
 * для этой библиотеки Имя модуля = Хорошие библиотеки.
 *
 * @package rzn.library
 * @author Andy Dune
 * @param string $page
 */

//Просто проверка, что пользователь имеет административные права
if(!$USER->IsAdmin()) return;



IncludeModuleLangFile(__FILE__);


CModule::IncludeModule('rzn.library');

/** @var string $page url текущей страницы + параметры. */
$page =
    Rzn\Library\Registry::getInstance()->getGlobal('APPLICATION')->GetCurPage() . '?mid=' . urlencode($mid) . '&amp;lang=' . LANGUAGE_ID;

    if(isset($_POST["save"]) && check_bitrix_sessid()) {

        $class_prefix = trim($_POST['class_prefix'], '\\ ');
        $parts = explode('\\', $class_prefix);
        foreach($parts as $key => $value)
        {
            $parts[$key] = ucfirst($value);
        }
        $class_prefix = implode('\\', $parts) . '\\';
        COption::SetOptionString("rzn.library", "autoload_class_prefix", $class_prefix);
        $class_folder = trim($_POST['class_folder'], '/ ');
        COption::SetOptionString("rzn.library", "autoload_class_folder", $class_folder);

        if (isset($_POST['class_is_helpers']) and $_POST['class_is_helpers'] == 'Y')
            COption::SetOptionString("rzn.library", "class_is_helpers", 'Y');
        else
            COption::SetOptionString("rzn.library", "class_is_helpers", 'N');

        LocalRedirect($page);
    }

$class_prefix =
    COption::GetOptionString("rzn.library", "autoload_class_prefix");

    $class_folder
        = COption::GetOptionString("rzn.library", "autoload_class_folder"); /**< Detailed description after the member */

$class_is_helpers = COption::GetOptionString("rzn.library", "class_is_helpers");

$aTabs = array(
	array("DIV" => "rzn_library_tab1", "TAB" => GetMessage("RZN_SETTINGS"), "ICON" => "settings", "TITLE" => GetMessage("RZN_LIBRARY_TITLE")),
);
/**
 * Создается вкладка в админпанели.
 *
 * @var $tabControl CAdminTabControl
 */
$tabControl
    = new CAdminTabControl("tabControl", $aTabs);

$tabControl->Begin();
?>
<form method="post" action="<?=$page?>">
<?$tabControl->BeginNextTab();?>
	<tr class="heading">
		<td colspan="2"><?=GetMessage("RZN_LIBRARY_CLASSES")?></td>
	</tr>
	<tr>
		<td valign="top" width="50%"><?=GetMessage("RZN_LIBRARY_CLASS_PREFIX")?>:</td>
		<td valign="top" width="50%"><input type="text" style="width: 90%" name="class_prefix" value="<?= $class_prefix ?>" /></td>
	</tr>
    <tr>
        <td valign="top" width="50%"><?=GetMessage("RZN_LIBRARY_CLASS_FOLDER")?>:</td>
        <td valign="top" width="50%"><input type="text" style="width: 90%" name="class_folder" value="<?= $class_folder ?>" /></td>
    </tr>

    <tr>
        <td valign="top" width="50%"><?=GetMessage("RZN_LIBRARY_IS_HELPERS")?>:</td>
        <td valign="top" width="50%"><input type="checkbox" name="class_is_helpers" value="Y"<?if($class_is_helpers=="Y"):?> checked="checked" <?endif;?> />
            </td>
    </tr>


<?$tabControl->Buttons();?>
	<input type="submit" name="save" value="<?=GetMessage("RZN_BTN_SAVE")?>">
	<?=bitrix_sessid_post();?>
<?$tabControl->End();?>
</form>