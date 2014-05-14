<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 03.06.13
 * Time: 16:40
 *
 * Рисование картинки из данных.
 * При встиавке атрибута width сравнивает с существующим значением в данных. Увеличивать не дает.
 *
 * Атрибут alt меянется на описание картинки, если такое есть.
 *
 */

namespace Rzn\Library\Component\Helper;

use Rzn\Library\Component\HelperAbstract;

class DrawImage extends HelperAbstract
{
    protected $_attr = array();
    protected $_data = array();

    public function __invoke($data, $attr = array(), $dimensions = array())
    {
        $this->_data = $data;
        $this->_attr = $attr;
        return $this;
    }


    public function get()
    {
        $data = $this->_data;
        $image = '<img src="' . $data['SRC'] . '"';

        if($this->_attr)
        {
            foreach($this->_attr as $attrKey => $attrValue)
            {
                if ($attrKey == 'width' and $attrValue > $data['WIDTH'])
                    $attrValue = $data['WIDTH'];
                if ($attrKey == 'alt' and $data['DESCRIPTION'])
                    $attrValue = $data['DESCRIPTION'];

                $image .= ' ' . $attrKey . '="' . htmlspecialchars($attrValue) . '"';
            }
        }

        $image .= '>';

        return $image;
    }


}