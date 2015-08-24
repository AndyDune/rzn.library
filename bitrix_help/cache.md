### Использование кеша без устаревших функций


```php

namespace MyManeSpace;

use Bitrix\Main\Data\Cache;

class Messages 
{
     ........
     
    public function getMessages($space)
    {
        $result = [];
        $cache = Cache::createInstance();
        $cacheId = 'messages.' . $space;
        if ($cache->initCache(3600, $cacheId, 'rzn')) {
            $res = $cache->getVars();
            if ($res and isset($res['messages'])) {
                return $res['messages'];
            }
        }

        $res = MessagesTable::getList(['filter' => ['space' => [$space, 'common']]]);
        while($row = $res->fetch()) {
            $result[$row['code']] = $row['message'] ;
        }
        $cache->startDataCache(3600, $cacheId, 'citimall');
        $cache->endDataCache(array("messages" => $result));

        return $result;
    }

```
