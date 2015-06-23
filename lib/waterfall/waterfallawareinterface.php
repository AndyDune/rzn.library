<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 16.06.2015                                      
  * ----------------------------------------------------
  *
  * Интерфейс для иньекции сервиса водопада в объект.
  *
  */


namespace Rzn\Library\Waterfall;


interface WaterfallAwareInterface
{
    public function setWaterfall($waterfall);

    public function getWaterfall();
}