<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 08.08.2015                                      
  * ----------------------------------------------------
  *
  *
  *
  * Пример конфигурации для использования инъектора параметров.
'injectTest' => [
    'handler' => 'setter',
    'options' => [
        'set' => 'params', // Обязатально. Определяет тип сеттера
        'params' => [], // Передаваемые значения
        'method' => 'setTest' // Если эот опустить будет использоваться по умолчанию setParams
    ]
],
  * Главный здесь ключ params
  * Хранит данные, которые будт переданы методу объекта для инъекции.
  * Есть варианты его использования:
  *
  * 1. Массив с одним или несколькими значениями - каждый элеметн этого массива передается как отдельный аргумент метода
  * 2. Не массив - приводится к массиву с одним элементом и см. 1
  *
  * Вместо params можно использовать param - всегда передается как один аргумент.
  * param может быть массивом, который передается одним аргументом.
  *
*/

namespace Rzn\Library\Injector\Handler\SetterHandler;
use Rzn\Library\Config as ConfigService;

class Params 
{
    /**
     * @param object $object
     * @param Config $params
     */
    public function execute($object, $params)
    {
        if ($params instanceof ConfigService) {
            $params = $params->toArray();
        }
        if (isset($params['param'])) {
            $params['params'] = [$params['param']];
        } else if (!isset($params['params'])) {
            $params['params'] = [];
        } else  if (!is_array($params['params'])) {
            $params['params'] = [$params['params']];
        }
        if (isset($params['method'])) {
            $method = $params['method'];
        } else {
            $method = 'setParams';
        }
        call_user_func_array([$object, $method], $params['params']);
    }

}