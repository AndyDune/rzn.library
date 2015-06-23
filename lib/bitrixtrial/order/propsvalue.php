<?
namespace Rzn\Library\BitrixTrial\Order;
class PropsValue
{
    protected $_orderId = null;
    protected $_propertiesCodeKey = array();

    protected $_propertyValuesWithCodeKey = array();

    /**
     * @var Props
     */
    protected $_propsObject = null;

    protected $_orderBaseData = null;

    public function __construct($orderId)
    {
        if (!\CModule::IncludeModule("sale"))
            throw new \Exception('Не загружен обязательный модуль sale.', 1);
        $this->_orderId = $orderId;
    }

    public function getOrderBaseData()
    {
        if ($this->_orderBaseData)
            return $this->_orderBaseData;
        $this->_orderBaseData = \CSaleOrder::GetByID($this->_orderId);
        return $this->_orderBaseData;
    }

    /**
     *
     * @return Props
     */
    public function getPropsObject()
    {
        if ($this->_propsObject)
            goto end;
        $this->_propsObject = new Props();
        end:
        return $this->_propsObject;
    }

    public function getPropertyWithCode($code)
    {
        if (array_key_exists($code, $this->_propertyValuesWithCodeKey))
            goto end;
        $property = $this->getPropsObject()->getPropertyWithCode($code);
        if (!$property)
            throw new \Exception('Не существует в заказе свойства с мнемоническим кодом: ' . $code, 0);

        $val = \CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array(
                "ORDER_ID" => $this->_orderId,
                "ORDER_PROPS_ID" => $property["ID"]
            )
        )->Fetch();

        if (!$val)
            $this->_propertyValuesWithCodeKey[$code] = null;
        else
            $this->_propertyValuesWithCodeKey[$code] = $val['VALUE'];

        end:
        return $this->_propertyValuesWithCodeKey[$code];
    }


    public function savePropertyWithCode($code, $value)
    {
        $property = $this->getPropsObject()->getPropertyWithCode($code);
        if (!$property)
            return null;
        if (
            $vals = \CSaleOrderPropsValue::GetList(
                array("SORT" => "ASC"),
                array(
                    "ORDER_ID" => $this->_orderId,
                    "ORDER_PROPS_ID" => $property["ID"]
                )
            )->Fetch()
        )
            return \CSaleOrderPropsValue::Update($vals["ID"], array(
                'VALUE' => $value
            ));
        else

            return \CSaleOrderPropsValue::Add(array(
                'NAME' => $property['NAME'],
                'CODE' => $property['CODE'],
                'ORDER_PROPS_ID' => $property['ID'],
                'ORDER_ID' => $this->_orderId,
                'VALUE' => $value,
            ));

    }

    /**
     * Возврат свойств заказа для экспорта.
     *
     * @return array
     */
    public function getPropsForExport()
    {

        $count = Array("nTopCount" => 1);


        $arFilter = array(
            "ID" => $this->_orderId
        );

        $arOrder = array("ID" => "DESC");

        $dbOrderList = \CSaleOrder::GetList(
            $arOrder,
            $arFilter,
            false,
            $count,
            array("ID", "LID", "PERSON_TYPE_ID", "PAYED", "DATE_PAYED", "EMP_PAYED_ID", "CANCELED", "DATE_CANCELED", "EMP_CANCELED_ID", "REASON_CANCELED", "STATUS_ID", "DATE_STATUS", "PAY_VOUCHER_NUM", "PAY_VOUCHER_DATE", "EMP_STATUS_ID", "PRICE_DELIVERY", "ALLOW_DELIVERY", "DATE_ALLOW_DELIVERY", "EMP_ALLOW_DELIVERY_ID", "PRICE", "CURRENCY", "DISCOUNT_VALUE", "SUM_PAID", "USER_ID", "PAY_SYSTEM_ID", "DELIVERY_ID", "DATE_INSERT", "DATE_INSERT_FORMAT", "DATE_UPDATE", "USER_DESCRIPTION", "ADDITIONAL_INFO", "PS_STATUS", "PS_STATUS_CODE", "PS_STATUS_DESCRIPTION", "PS_STATUS_MESSAGE", "PS_SUM", "PS_CURRENCY", "PS_RESPONSE_DATE", "COMMENTS", "TAX_VALUE", "STAT_GID", "RECURRING_ID")
        );


        $dbPaySystem = \CSalePaySystem::GetList(Array("ID" => "ASC"), Array("ACTIVE" => "Y"), false, false, Array("ID", "NAME", "ACTIVE"));
        while($arPaySystem = $dbPaySystem -> Fetch())
            $paySystems[$arPaySystem["ID"]] = $arPaySystem["NAME"];

        $dbDelivery = \CSaleDelivery::GetList(Array("ID" => "ASC"), Array("ACTIVE" => "Y"), false, false, Array("ID", "NAME", "ACTIVE"));
        while($arDelivery = $dbDelivery -> Fetch())
            $delivery[$arDelivery["ID"]] = $arDelivery["NAME"];

        $rsDeliveryHandlers = \CSaleDeliveryHandler::GetAdminList(array("SID" => "ASC"));
        while ($arHandler = $rsDeliveryHandlers->Fetch())
        {
            if(is_array($arHandler["PROFILES"]))
            {
                foreach($arHandler["PROFILES"] as $k => $v)
                {
                    $delivery[$arHandler["SID"].":".$k] = $v["TITLE"]." (".$arHandler["NAME"].")";
                }
            }
        }

        $dbExport = \CSaleExport::GetList();
        while($arExport = $dbExport->Fetch())
        {
            $arAgent[$arExport["PERSON_TYPE_ID"]] = unserialize($arExport["VARS"]);
        }

        $dateFormat = \CSite::GetDateFormat("FULL");


        $arOrder = $dbOrderList->Fetch();

            $agentParams = $arAgent[$arOrder["PERSON_TYPE_ID"]];
            $arProp = Array();

            $arProp["ORDER"] = $arOrder;
            if (IntVal($arOrder["USER_ID"]) > 0)
            {
                $dbUser = \CUser::GetByID($arOrder["USER_ID"]);
                if ($arUser = $dbUser->Fetch())
                    $arProp["USER"] = $arUser;
            }

            $dbOrderPropVals = \CSaleOrderPropsValue::GetList(
                array(),
                array("ORDER_ID" => $arOrder["ID"]),
                false,
                false,
                array("ID", "CODE", "VALUE", "ORDER_PROPS_ID", "PROP_TYPE")
            );
            while ($arOrderPropVals = $dbOrderPropVals->Fetch())
            {
                //$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $arOrderPropVals["VALUE"];
                if ($arOrderPropVals["PROP_TYPE"] == "CHECKBOX")
                {
                    if ($arOrderPropVals["VALUE"] == "Y")
                        $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = "true";
                    else
                        $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = "false";
                }
                elseif ($arOrderPropVals["PROP_TYPE"] == "TEXT" || $arOrderPropVals["PROP_TYPE"] == "TEXTAREA")
                {
                    $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $arOrderPropVals["VALUE"];
                }
                elseif ($arOrderPropVals["PROP_TYPE"] == "SELECT" || $arOrderPropVals["PROP_TYPE"] == "RADIO")
                {
                    $arVal = \CSaleOrderPropsVariant::GetByValue($arOrderPropVals["ORDER_PROPS_ID"], $arOrderPropVals["VALUE"]);
                    $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $arVal["NAME"];
                }
                elseif ($arOrderPropVals["PROP_TYPE"] == "MULTISELECT")
                {
                    $curVal = explode(",", $arOrderPropVals["VALUE"]);
                    for ($i = 0; $i < count($curVal); $i++)
                    {
                        $arVal = \CSaleOrderPropsVariant::GetByValue($arOrderPropVals["ORDER_PROPS_ID"], $curVal[$i]);
                        if ($i > 0)
                            $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] .=  ", ";
                        $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] .=  $arVal["NAME"];
                    }
                }
                elseif ($arOrderPropVals["PROP_TYPE"] == "LOCATION")
                {
                    $arVal = \CSaleLocation::GetByID($arOrderPropVals["VALUE"], LANGUAGE_ID);
                    $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] =  ($arVal["COUNTRY_NAME"].((strlen($arVal["COUNTRY_NAME"])<=0 || strlen($arVal["CITY_NAME"])<=0) ? "" : " - ").$arVal["CITY_NAME"]);
                    $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]."_CITY"] = $arVal["CITY_NAME"];
                    $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]."_COUNTRY"] = $arVal["COUNTRY_NAME"];
                }
                else
                {
                    $arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $arOrderPropVals["VALUE"];
                }
            }

            foreach($agentParams as $k => $v)
            {
                if(strpos($k, "REKV_") !== false)
                {
                    if(!is_array($v))
                    {
                        $agent["REKV"][$k] = $v;
                    }
                    else
                    {
                        if(strlen($v["TYPE"])<=0)
                        {
                            $agent["REKV"][$k] = $v["VALUE"];
                        }
                        else
                        {
                            $agent["REKV"][$k] = $arProp[$v["TYPE"]][$v["VALUE"]];
                        }
                    }
                }
                else
                {
                    if(!is_array($v))
                    {
                        $agent[$k] = $v;
                    }
                    else
                    {
                        if(strlen($v["TYPE"])<=0)
                        {
                            $agent[$k] = $v["VALUE"];
                        }
                        else
                        {
                            $agent[$k] = $arProp[$v["TYPE"]][$v["VALUE"]];
                        }
                    }
                }
            }

        $arProp['ORDER']['PAY_SYSTEM_NAME'] = '';
        $arProp['ORDER']['DELIVERY_NAME'] = '';

        if (array_key_exists($arProp['ORDER']['DELIVERY_ID'], $delivery))
            $arProp['ORDER']['DELIVERY_NAME'] = $delivery[$arProp['ORDER']['DELIVERY_ID']];


        if (array_key_exists($arProp['ORDER']['PAY_SYSTEM_ID'], $paySystems))
            $arProp['ORDER']['PAY_SYSTEM_NAME'] = $paySystems[$arProp['ORDER']['PAY_SYSTEM_ID']];

        return array(
            'delivery' => $delivery,
            'pay_systems' => $paySystems,
            'props' => $arProp,
            'agent' => $agent
        );

    }


}