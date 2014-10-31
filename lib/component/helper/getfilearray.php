<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 */

namespace Rzn\Library\Component\Helper;

use Rzn\Library\Component\HelperAbstract;
use CFile;

class GetFileArray extends HelperAbstract
{
    protected $id = null;
    protected $params = array();


    public function __invoke($id, $params = array())
    {
        $this->id = $id;
        $this->params = $params;
        return $this->get();
        //return $this;
    }

    public function get()
    {
        if (!$this->id) {
            return null;
        }
        $result = CFile::GetFileArray($this->id);
        if ($result and isset($this->params['width'])) {
            $sizes = array('width' => $this->params['width']);
            if (isset($this->params['height'])) {
                $sizes['height'] = $this->params['height'];
            } else {
                // Пропорционально восстанавливаем высоту
                $sizes['height'] = floor($result['HEIGHT'] * $sizes['width'] / $result['WIDTH']);
            }
            $result = CFile::ResizeImageGet($result, $sizes, BX_RESIZE_IMAGE_PROPORTIONAL, true);
        }
        return $result;
    }


} 