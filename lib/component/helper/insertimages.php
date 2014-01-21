<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 14.05.13
 * Time: 15:18
 *
 * Замена в тексте специальных сомволов на картинку из структуированных данных.
 * Нужно во избежание "диких" файлов и быстрой замены картинок в тексте.
 *
 * Используется ключ <!-- insert_image(<порядковый номер в массиве>)-->
 *
 * Пример: <!-- insert_image(1)-->
 *
 *
 * Типовая информация о картинке. Пример:
        [ID] => 1252
        [TIMESTAMP_X] => 14.05.2013 15:38:23
        [MODULE_ID] => iblock
        [HEIGHT] => 600
        [WIDTH] => 800
        [FILE_SIZE] => 108821
        [CONTENT_TYPE] => image/jpeg
        [SUBDIR] => iblock/031
        [FILE_NAME] => 03135317a7b4909d3b03ffc651aa6964.jpg
        [ORIGINAL_NAME] => 10cc8562a711815657f3a9405c5.jpg
        [DESCRIPTION] =>
        [HANDLER_ID] =>
        [~src] =>
        [SRC] => /upload/iblock/031/03135317a7b4909d3b03ffc651aa6964.jpg
 *
 * Пример использования
 * <?= $arResult['helper']->insertImages($arResult['DETAIL_TEXT'], $arResult["MORE_PHOTO"], '<p style="text-align: center;">#img</p>', array('width' => 600)) ?>
 *
 */

namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;

class InsertImages extends HelperAbstract
{
    protected $_template = null;
    protected $_attr = null;

    protected $_images = array();

    /**
     * @param $text Целефой текст
     * @param $imagesArray Массив с информацией об изображениях
     * @param null|string $template Шаблон для вставки картинок
     * @param null|array $attr Массив атрибутов
     * @return $this
     */
    public function __invoke($text, $imagesArray, $template = null, $attr = null)
    {
        if (is_array($attr))
            $this->_attr = $attr;
        $this->_template = $template;
        $images = array();
        $key = 1;
        foreach($imagesArray as $image)
        {
            $images[$key] = $image;
            $key++;
        }
        $this->_images = $images;
        $this->_result = preg_replace_callback('/<!--\s*insert_image\s*\(\s*(.*?)\s*\)\s*-->/iS', array($this, '_replaceItem'), $text);
        return $this;
    }

    protected function _replaceItem($matches)
    {
        if (!isset($matches[1]) or !array_key_exists($matches[1], $this->_images))
            return '';

        $data = $this->_images[$matches[1]];

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

        if ($this->_template)
        {
            $image = str_replace(array('#img', '#description'), array($image, $data['DESCRIPTION']), $this->_template);
        }

        return $image;
    }

}