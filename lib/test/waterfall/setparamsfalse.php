<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 15.12.2015                                  |
 * ----------------------------------------------------
 *
 * Исключительно для тестов.
 * Проверка использования интерфейса для создания описания фабрики шага водопада.
 *
 */


namespace Rzn\Library\Test\Waterfall;
use Rzn\Library\Waterfall\WaterfallInitializationAwareInterface;

class SetParamsFalse implements WaterfallInitializationAwareInterface
{
    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function doIt($params, $result)
    {
        $result['x'] = false;
    }

    /**
     * @param string $type drop|error|final
     * @return mixed
     */
    public function getFunctionForWaterfall($type = 'drop')
    {
        $self = $this;
        return function($params, $result) use ($self) {
            $self->doIt($params, $result);
        };
    }

}