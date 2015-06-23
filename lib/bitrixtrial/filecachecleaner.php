<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 15.08.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\BitrixTrial;
use CFileCacheCleaner as CacheCleaner;

class FileCacheCleaner 
{

    /**
     * Тип кеша для убийства
     * Бывают: all, menu, managed, html
     * @var string
     */
    protected $cacheType = 'all';

    protected $deleteOld = true;


    protected $deleted = 0;


    public function make()
    {

        $bDoNotCheckExpiredDate =  !$this->deleteOld;
        $currentTime = mktime();
        $endTime = time()+5;
        $path = '';

        $obCacheCleaner = new CacheCleaner($this->cacheType);
        if(!$obCacheCleaner->InitPath($path))
        {
            return false;
        }

        $obCacheCleaner->Start();
        while($file = $obCacheCleaner->GetNextFile())
        {
            if(is_string($file))
            {
                $date_expire = $obCacheCleaner->GetFileExpiration($file);
                if($date_expire)
                {
                    $file_size = filesize($file);

                    //$_SESSION["CACHE_STAT"]["scanned"]++;
                    //$_SESSION["CACHE_STAT"]["space_total"]+=$file_size;

                    if(
                        $bDoNotCheckExpiredDate
                        || ($date_expire < $currentTime)
                    )
                    {
                        if(@unlink($file))
                        {
                            $this->deleted++;
                            //$_SESSION["CACHE_STAT"]["space_freed"]+=$file_size;
                        }
                        else
                        {
                            //$_SESSION["CACHE_STAT"]["errors"]++;
                        }
                    }
                }

                // Для пошаговости этого.
                //if(time() >= $endTime)
                //    break;
            }

        }

    }
} 