<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 04.10.13
 * Time: 10:50
 *
 *
    CREATE TABLE `rzn_ip_to_location` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `ip` varchar(20) DEFAULT NULL,
        `city` varchar(50) DEFAULT NULL,
        `location_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `ip` (`ip`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */

namespace Rzn\Library;
use Rzn\Library\Db\BuildSaveDataString;


class LocationFromIp
{
    /**
     * @var \CDatabase
     */
    protected $_db;

    protected $_table = 'rzn_ip_to_location';

    public function __construct()
    {
        $reg = Registry::getInstance();
        /** @var \CDatabase $db */
        $db = $reg->getGlobal('DB');
        $this->_db = $db;
    }

    public function getStoredData($ip)
    {
        $sql = 'SELECT * FROM `' . $this->_table  . '` WHERE `ip` = "' . $ip . '"';
        $result = $this->_db->Query($sql)->Fetch();
        if ($result)
        {
            return $result;
        }
        return null;
    }

    public function extractFromLocationArray($city, $array)
    {
        foreach($array as $key => $loc)
        {
            $c = trim(preg_replace('|\(.*\)|', '', $loc['CITY_NAME']));
            if (strtolower($c) == strtolower($city))
            {
                $loc['POSITION'] = $key;
                return $loc;
            }
        }
        return null;
    }

    public function save($data)
    {
        $fields = array(
            'ip'          => array('type' => 'string', 'len' => 20),
            'city'        => array('type' => 'string', 'len' => 50),
            'location_id' => array('type' => 'int')
        );
        $build = new BuildSaveDataString($fields);

        $insertString = $build->getInsert($data);
        if (!$insertString)
            return false;

        $sql = 'INSERT INTO `' . $this->_table  . '`'
            . $insertString;

        $this->_db->Query($sql);
    }

}