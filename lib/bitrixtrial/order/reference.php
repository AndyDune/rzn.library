<?
/**
 * Некоторые вспомогательные методы.
 *
 */
namespace Rzn\Library\BitrixTrial\Order;
class Reference
{

    /**
     * Список статусов заказа.
     *
     * @return array
     */
    public function getStatusList()
    {
        $arOrderStatus = array();
        $dbStatusList = CSaleStatus::GetList(
            array("SORT" => "ASC"),
            array("LID" => LANGUAGE_ID),
            false,
            false,
            array("ID", "NAME")
        );
        while ($arStatusList = $dbStatusList->Fetch())
            $arOrderStatus[htmlspecialcharsbx($arStatusList["ID"])] = htmlspecialcharsbx($arStatusList["NAME"]);
        return $arOrderStatus;
    }

    /**
     * Список методов доставки.
     *
     * @return array
     */
    public function getDeliveryList()
    {
        $arDelivery = array();
        $dbDeliveryList = CSaleDelivery::GetList(
            array("SORT" => "ASC"),
            array()
        );
        while ($arDeliveryList = $dbDeliveryList->Fetch())
            $arDelivery[$arDeliveryList["ID"]] = htmlspecialcharsbx($arDeliveryList["NAME"]);
        return $arDelivery;
    }

    /**
     * Список платежгый систем.
     *
     * @return array
     */
    public function getPaySystemList()
    {
        $arPaySystem = array();
        $dbPaySystemList = CSalePaySystem::GetList(
                                                    array("SORT"=>"ASC"),
                                                    array()
        );
        while ($arPaySystemList = $dbPaySystemList->Fetch())
            $arPaySystem[$arPaySystemList["ID"]] = htmlspecialcharsbx($arPaySystemList["NAME"]);
        return $arPaySystem;
    }

}