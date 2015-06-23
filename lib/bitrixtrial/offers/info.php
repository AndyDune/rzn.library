<?
namespace Rzn\Library\BitrixTrial\Offers;

class Info
{
    protected $_parentId = null;
    protected $_offersIblockId = null;

    protected $_offerPropsExists = false;

    protected $_arIblockOfferPropsTotalInfo = array();
    protected $_arIblockOfferProps = array();
    protected $_arIblockOfferPropsSKU = array();

    public function __construct($parentId)
    {
        $this->_parentId = $parentId;
        $arOffersIblock = \CIBlockPriceTools::GetOffersIBlock($this->_parentId);
        $this->_offersIblockId = is_array($arOffersIblock)? $arOffersIblock["OFFERS_IBLOCK_ID"]: 0;
    }

    public function retrieveProperties($excludeProperties = null)
    {
        if (!$this->_offersIblockId)
            return null;

        if (!$excludeProperties)
            $excludeProperties = array();
        elseif(!is_array($excludeProperties))
            $excludeProperties = array($excludeProperties);

        $dbOfferProperties = \CIBlock::GetProperties($this->_offersIblockId, Array(), Array("!XML_ID" => "CML2_LINK"));

        while($arOfferProperties = $dbOfferProperties->Fetch())
        {
            if (in_array($arOfferProperties['CODE'], $excludeProperties))
                continue;
            $this->_arIblockOfferPropsTotalInfo[] = $arOfferProperties;
            /**
             * Возможны все свойства
             */

            $this->_arIblockOfferProps[]    = array("CODE" => $arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
            $this->_arIblockOfferPropsSKU[] = array("CODE" => "SKU_".$arOfferProperties["CODE"], "NAME" => $arOfferProperties["NAME"]);
            $this->_offerPropsExists = true;
        }
        return $this->_offerPropsExists;
    }

    public function getIblockOfferProps()
    {
        return $this->_arIblockOfferPropsTotalInfo;
    }

    public function getIblockOfferPropsCodeAndName()
    {
        return $this->_arIblockOfferProps;
    }

    public function getIblockOfferPropsCodeAndNameSKU()
    {
        return $this->_arIblockOfferPropsSKU;
    }


    public function getOffersIblockId()
    {
        return $this->_offersIblockId;
    }


    public function isOfferPropsExists()
    {
        return $this->_offerPropsExists;
    }

}



