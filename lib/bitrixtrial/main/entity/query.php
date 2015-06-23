<?php
/**
 * User: Andrey Ryzhov
 * Email: info@rznW.ru
 * Phone: 8 (4912) 51-10-23
 *
 *  Пример использования этого класса.
 *
 *  $filter - стандартный массив фильтра для orm битрикса.
 *
    $main_query = new Query('Citimall\SellerCabinet\Table\ShopAdTable');
    $main_query->setFilter($filter);
    $query = 'SELECT * FROM ' . ShopAdTable::getTableName()
            . ' WHERE ' . $main_query->buildWhere()
            . ' ORDER BY RAND()';


    $resultDb = $main_query->query($query);
    $result = $resultDb->fetchAll();
    return $result;

 */

namespace Rzn\Library\BitrixTrial\Main\Entity;
use Bitrix\Main\Entity\Query as BitrixQuery;


class Query extends BitrixQuery
{
    /**
     * Метод сделан публичным.
     * @return string
     */
    public function buildWhere($prefix = ' WHERE ')
    {
        //pr($this->filter);
        $this->replaced_aliases = array();
        $this->setFilterChains($this->filter);
        $this->divideFilter($this->filter);
        $res = parent::buildWhere();
        if ($res) {
            $res = $prefix . $res;
        }
        return $res;
    }

    public function query($query)
    {
        return parent::query($query);
    }
}