<?php
/**
 * Описание колличества с правильным склонением.
 * 1 огурец
 * 2 огурца
 * 10 огурцов

 */
namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

class PluralForm extends HelperAbstract
{
    /**
     *
     * @param integer $n рассматриваемое число
     * @param string $form1 огурец
     * @param string $form2 огурца
     * @param string $form3 огурцов
     * @return Www_View_Helper_PluralForm
     */
    public function __invoke($n = null, $form1 = null, $form2 = null, $form3 = null)
    {
        $this->_result = $this->_pluralForm($n, $form1, $form2, $form3);
        return $this;
    }


    /**
     *
     * @param integer $n рассматриваемое число
     * @param string $form1 огурец
     * @param string $form2 огурца
     * @param string $form3 огурцов
     * @return string нужный вариант
     */
    protected function _pluralForm($n, $form1, $form2, $form3)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $form3;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;
        return $form3;
    }

}
