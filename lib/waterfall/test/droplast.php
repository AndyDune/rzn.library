<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 13.08.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Waterfall\Test;


class DropLast 
{
    protected $im;

    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        pr([
            'message' => 'Последний дроп в серии:' . $result->getCurrentFunction(),
            'title' => 'Разделемые данные',
            'data' => $result->getSharedResults()]);
        $sm = $this->getInnerManager();
        if ($sm->has('test')) {
            pr('Есть сообщение');
        }
        pr($sm->receive('test', 1));
        if (!$sm->has('test')) {
            pr('Нет сообщения');
        }

    }

    public function setIM($service)
    {

    }

    /**
     * Внедрение сервиса внутренних сообщений
     *
     * @param \Rzn\Library\InnerMessage\Manager $service
     * @return mixed
     */
    public function setInnerManager($service)
    {
        $this->im = $service;
    }

    /**
     * Получить сервис внутренних сообщений
     *
     * @return \Rzn\Library\InnerMessage\Manager
     */
    public function getInnerManager()
    {
        return $this->im;
    }

}