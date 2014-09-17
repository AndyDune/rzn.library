<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * ----------------------------------------------------
 *
 *
 *
 */

namespace Rzn\Library;
use Bitrix\Main\Data\Cache;

trait DataCacheTrait
{
    /**
     * Для произвольного кеширования.
     *
     * @param $method Имя метода или замыкание
     * @param $key ключ кеша. Не кодировать!
     * @param int $time
     * @return mixed
     */
    protected function _cacheResults($method, $key, $time = 360000, $dir = '/citimall/class')
    {
        $key = md5($key);

        $cache = Cache::createInstance();

        if($cache->initCache($time, $key, $dir)) {
            $data = $cache->GetVars();
        } else {
            $cache->StartDataCache($time, $key, $dir);
            if (is_callable($method)) {
                $data = $method();
            } else {
                $data = $this->$method();
            }
            $cache->EndDataCache($data);
        }

        return $data;
    }

    /**
     * @param $key ключ
     * @param string $dir
     * @return bool
     */
    protected function _clearCache($key, $dir = '/citimall/class')
    {
        $key = md5($key);
        $cache = Cache::createInstance();
        $cache->clean($key, $dir);
        return true;
    }

} 