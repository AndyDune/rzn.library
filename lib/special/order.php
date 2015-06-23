<?
namespace Rzn\Library\Special;
class Order
{


    /**
     * Проверка позиции корзины на возможность покупки.
     * Вводится дополнительный флаг, который будет отображаться в корзине, но для системы отрицательный.
     *
     * @param $word
     * @return bool
     */
    static public function isCanBuy($word)
    {
        if (in_array($word, array('Y', 'D')))
            return true;
        return false;
    }


    static public function markBasketForSeller($basket, $sellerId)
    {
        foreach($basket as $sid => $basketArray)
        {
            if ($sid == $sellerId)
            {
                foreach($basketArray as $id)
                    \CSaleBasket::Update($id, array('CAN_BUY' => 'Y'));
            }
            else
            {
                foreach($basketArray as $id)
                    \CSaleBasket::Update($id, array('CAN_BUY' => 'N'));
            }
        }
        return false;
    }


}