<?
/**
 * Работа сос свойствами инфоблока.
 *
 */

namespace Rzn\Library\BitrixTrial\Iblock;
use Rzn\Library\Exception;
class Property
{
    protected $_iblockId = null;
    public function __construct($iblockId = null)
    {
        $this->_iblockId = $iblockId;
    }

    /**
     * Список свойст спросить.
     *
     * @return array
     * @throws \Rzn\Library\Exception
     */
    public function getListProperty()
    {
        if (!$this->_iblockId)
            throw new Exception('Не передан обязательный параметр конмтруктору класса', 1);
        $properties = CIBlockProperty::GetList(Array("sort" => "asc"), Array("ACTIVE" => "Y", 'IBLOCK_ID' => $this->_iblockId));
        $props = array();
        while ($prop_fields = $properties->GetNext())
        {
            $props[] = $prop_fields;
        }
        return $props;
    }

}