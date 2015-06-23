<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 20.08.13
 * Time: 17:53
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\BitrixTrial\Catalog\Discount;
class CouponList
{
    protected $_arNavStartParams = false;

    /**
        Массив параметров, по которым строится фильтр выборки. Имеет вид:
        array(
        "[модификатор1][оператор1]название_поля1" => "значение1",
        "[модификатор2][оператор2]название_поля2" => "значение2",
        . . .
        )
        Удовлетворяющие фильтру записи возвращаются в результате, а записи, которые не удовлетворяют условиям фильтра, отбрасываются.
        Допустимыми являются следующие модификаторы:
        ! - отрицание;
        + - значения null, 0 и пустая строка так же удовлетворяют условиям фильтра.
        Допустимыми являются следующие операторы:
        >= - значение поля больше или равно передаваемой в фильтр величины;
        > - значение поля строго больше передаваемой в фильтр величины;
        <= - значение поля меньше или равно передаваемой в фильтр величины;
        < - значение поля строго меньше передаваемой в фильтр величины;
        @ - значение поля находится в передаваемом в фильтр разделенном запятой списке значений;
        ~ - значение поля проверяется на соответствие передаваемому в фильтр шаблону;
        % - значение поля проверяется на соответствие передаваемой в фильтр строке в соответствии с языком запросов.
        "название поля" может принимать значения:
        ID - код (ID) купона (число);
        DISCOUNT_ID - код (ID) скидки (число);
        ACTIVE - фильтр по активности (Y|N); передача пустого значения ("ACTIVE"=>"") выводит купоны без учета их состояния (строка);
        ONE_TIME - флаг однократного использования купона (Y|N); передача пустого значения ("ONE_TIME"=>"") выводит купоны без учета их типа (строка);
        COUPON - код купона (маска);
        DATE_APPLY - дата применения купона (дата);
        DESCRIPTION - комментарий (маска);
        Значения фильтра - одиночное значение или массив значений.
        Необязательное. По умолчанию купоны не фильтруются.
     *
     *
     */
    protected $_arFilter = array();

    protected $_arOrder = array();


    public function __construct()
    {
        \CModule::IncludeModule("catalog");
    }


    /**
     * @param $key
     * @param $value
     */
    public function addFilter($key, $value)
    {
        $this->_arFilter[$key] = $value;
    }

    public function setCount($count = false)
    {
        if (!$count)
            $this->_arNavStartParams = false;
        else
        {
            $this->_arNavStartParams = array('nTopCount' => $count);
        }
    }

    public function get($toArray = false)
    {
        $result = \CCatalogDiscountCoupon::GetList (
               $this->_arOrder,
               $this->_arFilter,
               false,
               $this->_arNavStartParams,
               array()
            );

        if ($toArray)
        {
            $temp = array();
            while($r = $result->Fetch())
            {
                $temp[] = $r;
            }
            $result = $temp;
        }

        return $result;
    }

}