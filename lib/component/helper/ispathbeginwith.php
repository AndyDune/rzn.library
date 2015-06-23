<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 14.01.15                                      
  * ----------------------------------------------------
  *
  */

namespace Rzn\Library\Component\Helper;
use Rzn\Library\Component\HelperAbstract;
use Rzn\Library\ServiceManager\BitrixApplicationInterface;

class IsPathBeginWith extends HelperAbstract implements BitrixApplicationInterface
{
    /**
     * @var \CMain
     */
    protected $application;

    protected $curPage;
    public function __invoke($variants)
    {
        if (!is_array($variants)) {
            $variants = [$variants];
        }
        foreach($variants as $variant) {
            if (strpos($this->curPage, $variant) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \CMain $application
     * @return mixed
     */
    public function setApplication($application)
    {
        $this->application = $application;
        $this->curPage = $this->application->GetCurPage();
    }

    /**
     * @return \CMain
     */
    public function getApplication()
    {
        return $this->application;
    }


}
