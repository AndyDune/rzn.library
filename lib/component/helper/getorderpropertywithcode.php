<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 05.06.13
 * Time: 15:28
 * To change this template use File | Settings | File Templates.
 */

namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;
use Rzn\Library\BitrixTrial\Order\PropsValue;

class GetOrderPropertyWithCode extends HelperAbstract
{
    protected $_orderPropsObject = '';

    public function __invoke($propertyCode, $orderId)
    {
        $this->_orderPropsObject = new PropsValue($orderId);
        $this->_result = $this->_orderPropsObject->getPropertyWithCode($propertyCode);
        return $this;
    }

}