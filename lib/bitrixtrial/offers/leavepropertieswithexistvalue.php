<?
/**
 * Анализ существующих парметров SKU. Отбрасываются несуществующие.
 * В файле компонента необходимо отклюсчить принудительную выборку только указанных параметров.
 *
 * В файле components\bitrix\catalog\citimall\bitrix\catalog.section\bar\result_modifier.php это
 *
 *         if (!in_array($arOfferProperties["CODE"],$arParams["OFFERS_PROPERTY_CODE"]))
                continue;
 *
 */
namespace Rzn\Library\BitrixTrial\Offers;

/**
 *
 * Функции класса полностью премещены в \Citimall\Library\Catalog\Element\Context\Sku
 *
 * Class LeavePropertiesWithExistValue
 * @package Rzn\Library\BitrixTrial\Offers
 * @deprecated
 */
class LeavePropertiesWithExistValue
{
    protected $_offer = null;
    protected $_arIblockOfferProps = array();
    protected $_arIblockOfferPropsSKU = array();

    protected $_good = true;

    protected $_keyPropertyName = '';

    public function __construct($offer, $arIblockOfferProps, $arIblockOfferPropsSKU = null)
    {
        $this->_offer = $offer;

        if(isset($this->_offer['PROPERTIES']['CITIMALL_OFFER_KEY_NAME']['VALUE']) and $this->_offer['PROPERTIES']['CITIMALL_OFFER_KEY_NAME']['VALUE']
        and isset($this->_offer['PROPERTIES']['CITIMALL_OFFER_KEY_VALUE']['VALUE'])
        )
        {
            $this->_keyPropertyName
                = $this->_offer['PROPERTIES']['CITIMALL_OFFER_KEY_VALUE']['NAME']
                = $this->_offer['PROPERTIES']['CITIMALL_OFFER_KEY_VALUE']['~NAME']
                = $this->_offer['PROPERTIES']['CITIMALL_OFFER_KEY_NAME']['VALUE'];
        }

        //print_r($this->_offer);
        if ($arIblockOfferProps instanceof Info)
        {
            $this->_arIblockOfferProps = $arIblockOfferProps->getIblockOfferPropsCodeAndName();
            $this->_arIblockOfferPropsSKU = $arIblockOfferProps->getIblockOfferPropsCodeAndNameSKU();
        }
        else
        {
            $this->_arIblockOfferProps = $arIblockOfferProps;
            $this->_arIblockOfferPropsSKU = $arIblockOfferPropsSKU;
        }

        $this->_process();
        //print_r($this->_arIblockOfferPropsSKU);
    }

    public function getIblockOfferProps()
    {
        return $this->_arIblockOfferProps;
    }

    public function getIblockOfferPropsSKU()
    {
        return $this->_arIblockOfferPropsSKU;
    }

    public function isOfferExist()
    {
        return $this->_good;
    }


    protected function _process()
    {
        $codeToDelete = array('CITIMALL_OFFER_KEY_NAME');
        $codeToLeave = 'CITIMALL_OFFER_KEY_VALUE';
        foreach($this->_offer["PROPERTIES"] as $code => $arProp)
        {
            if (!isset($arProp['VALUE']) or !$arProp['VALUE'])
            {
                $codeToDelete[] = $code;
            }
        }
        $new = array();
        foreach($this->_arIblockOfferProps as $key => $value)
        {
            //if (!in_array($value['CODE'], $codeToDelete))
            if($value['CODE'] == $codeToLeave)
            {
                if ($value['CODE'] == 'CITIMALL_OFFER_KEY_VALUE')
                    $value['NAME'] = $this->_keyPropertyName;
                $new[] = $value;
            }
        }
        $this->_arIblockOfferProps = $new;
        $new = array();
        foreach($this->_arIblockOfferPropsSKU as $key => $value)
        {
            //if (!in_array(substr($value['CODE'], 4), $codeToDelete))
            if(substr($value['CODE'], 4) == $codeToLeave)
            {
                if ($value['CODE'] == 'SKU_CITIMALL_OFFER_KEY_VALUE')
                    $value['NAME'] = $this->_keyPropertyName;
                $new[] = $value;
            }
        }
        $this->_arIblockOfferPropsSKU = $new;

        if (!count($this->_arIblockOfferProps))
            $this->_good = false;
    }

}