<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 22.05.2015
 * ----------------------------------------------------
 *
 * Код взят тут: https://github.com/mrXCray/PhoneCodes
 * На хабре: http://habrahabr.ru/post/102352/
 */


namespace Rzn\Library\Component\Helper;

use Rzn\Library\Component\HelperAbstract;

class PhoneNumberFormat extends HelperAbstract
{
    protected $phoneCodes = Array(
        '9162' => Array(
            'name' => 'Cocos',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '6723' => Array(
            'name' => 'Norfolk',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '4175' => Array(
            'name' => 'Liechtenstein',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '3428' => Array(
            'name' => 'Canary',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '3395' => Array(
            'name' => 'Corsica',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1907' => Array(
            'name' => 'Alaska',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1876' => Array(
            'name' => 'Jamaica',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1869' => Array(
            'name' => 'St.KittsAndNevis',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1868' => Array(
            'name' => 'TrinidadAndTobago',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1809' => Array(
            'name' => 'Dominican',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1787' => Array(
            'name' => 'PuertoRico',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1784' => Array(
            'name' => 'St.VincentAndTheGrenadines',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1767' => Array(
            'name' => 'Dominica',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1758' => Array(
            'name' => 'St.Lucia',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1671' => Array(
            'name' => 'Guam',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1670' => Array(
            'name' => 'CommonwealthOfTheNorthernMarianaIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1664' => Array(
            'name' => 'Montserrat',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1649' => Array(
            'name' => 'Turks&Caicos',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1473' => Array(
            'name' => 'Grenada',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1441' => Array(
            'name' => 'Bermuda',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1345' => Array(
            'name' => 'CaymanIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1340' => Array(
            'name' => 'USVirginIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1284' => Array(
            'name' => 'Bahamas',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1268' => Array(
            'name' => 'AntiguaAndBarbuda',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1264' => Array(
            'name' => 'Anguilla',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '1246' => Array(
            'name' => 'Barbados',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '998' => Array(
            'name' => 'Uzbekistan',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(71, 74, 65, 67, 72, 75, 79, 69, 61, 66, 76, 62, 73, 677, 673),
            'exceptions_max' => 3,
            'exceptions_min' => 2
        ),
        '996' => Array(
            'name' => 'Kyrgyzstan',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(31, 37, 313, 39, 35, 32, 34),
            'exceptions_max' => 3,
            'exceptions_min' => 2
        ),
        '995' => Array(
            'name' => 'Georgia',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(32, 34),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '994' => Array(
            'name' => 'Azerbaijan',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(12, 1445, 1302),
            'exceptions_max' => 4,
            'exceptions_min' => 2
        ),
        '993' => Array(
            'name' => 'Turkmenistan',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '992' => Array(
            'name' => 'Tajikistan',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '977' => Array(
            'name' => 'Nepal',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '976' => Array(
            'name' => 'Mongolia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '975' => Array(
            'name' => 'Bhutan',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '974' => Array(
            'name' => 'Qatar',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(48, 59, 550, 551, 552, 553, 554, 555, 556, 557, 558, 559, 222, 223, 224, 225, 226, 227),
            'exceptions_max' => 3,
            'exceptions_min' => 2
        ),
        '973' => Array(
            'name' => 'Bahrain',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '972' => Array(
            'name' => 'Israel',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(50, 51, 52, 53, 58),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '971' => Array(
            'name' => 'UnitedArabEmirates',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(5079),
            'exceptions_max' => 4,
            'exceptions_min' => 4
        ),
        '969' => Array(
            'name' => 'Yemen,South',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(8),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '968' => Array(
            'name' => 'Oman',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '967' => Array(
            'name' => 'Yemen,North',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '966' => Array(
            'name' => 'SaudiArabia',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '965' => Array(
            'name' => 'Kuwait',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '964' => Array(
            'name' => 'Iraq',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(1, 43, 49, 25, 62, 36, 32, 50, 23, 60, 42, 33, 24, 37, 53, 21, 30, 66),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '963' => Array(
            'name' => 'Syria',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '962' => Array(
            'name' => 'Jordan',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(59, 79, 73, 74, 17),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '961' => Array(
            'name' => 'Lebanon',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '960' => Array(
            'name' => 'Maldives',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '886' => Array(
            'name' => 'Taiwan',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(89, 90, 91, 92, 93, 96, 60, 70, 94, 95),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '880' => Array(
            'name' => 'Bangladesh',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(51, 2, 41, 81, 91, 31),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '856' => Array(
            'name' => 'Laos',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(9),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '855' => Array(
            'name' => 'Cambodia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1881, 1591, 1720),
            'exceptions_max' => 4,
            'exceptions_min' => 4
        ),
        '853' => Array(
            'name' => 'Macau',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '852' => Array(
            'name' => 'HongKong',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '850' => Array(
            'name' => 'Korea,Dem.PeoplesRepublic',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '692' => Array(
            'name' => 'MarshallIslands',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(873),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '691' => Array(
            'name' => 'Micronesia',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '690' => Array(
            'name' => 'Tokelau',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '689' => Array(
            'name' => 'FrenchPolynesia',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '688' => Array(
            'name' => 'Tuvalu',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '687' => Array(
            'name' => 'NewCaledonia',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '686' => Array(
            'name' => 'Kiribati',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '685' => Array(
            'name' => 'WesternSamoa',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '684' => Array(
            'name' => 'AmericanSamoa',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '683' => Array(
            'name' => 'NiueIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '682' => Array(
            'name' => 'CookIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '681' => Array(
            'name' => 'WallisAndFutuna',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '680' => Array(
            'name' => 'Palau',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '679' => Array(
            'name' => 'Fiji',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '678' => Array(
            'name' => 'Vanuatu',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '677' => Array(
            'name' => 'SolomonIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '676' => Array(
            'name' => 'Tonga',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '675' => Array(
            'name' => 'PapuaNewGuinea',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '674' => Array(
            'name' => 'Nauru',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '673' => Array(
            'name' => 'Brunei',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '672' => Array(
            'name' => 'ChristmasIsland',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '671' => Array(
            'name' => 'Guam',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '670' => Array(
            'name' => 'NorthernMarianaIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(2348),
            'exceptions_max' => 4,
            'exceptions_min' => 4
        ),
        '599' => Array(
            'name' => 'NetherlandsAntilles',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(46),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '598' => Array(
            'name' => 'Uruguay',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(42, 2),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '597' => Array(
            'name' => 'Suriname',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '596' => Array(
            'name' => 'Martinique',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '595' => Array(
            'name' => 'Paraguay',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(541, 521),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '594' => Array(
            'name' => 'FrenchGuiana',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '593' => Array(
            'name' => 'Ecuador',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '592' => Array(
            'name' => 'Guyana',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '591' => Array(
            'name' => 'Bolivia',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(69, 4, 2, 92, 52, 3, 46),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '590' => Array(
            'name' => 'FrenchAntilles',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '509' => Array(
            'name' => 'Haiti',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(330, 420, 510, 851),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '508' => Array(
            'name' => 'SaintPierreEtMiquelon',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '507' => Array(
            'name' => 'Panama',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '506' => Array(
            'name' => 'Costa',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '505' => Array(
            'name' => 'Nicaragua',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '504' => Array(
            'name' => 'Honduras',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '503' => Array(
            'name' => 'ElSalvador',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '502' => Array(
            'name' => 'Guatemala',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '501' => Array(
            'name' => 'Belize',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '500' => Array(
            'name' => 'Falkland',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '421' => Array(
            'name' => 'SlovakRepublic',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(7, 89, 95, 92, 91),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '420' => Array(
            'name' => 'CzechRepublic',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(5, 49, 67, 66, 17, 48, 35, 68, 69, 40, 19, 2, 47, 38),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '389' => Array(
            'name' => 'Macedonia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(903, 901, 902),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '387' => Array(
            'name' => 'BosniaAndHerzegovina',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '386' => Array(
            'name' => 'Slovenia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(608, 602, 601),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '385' => Array(
            'name' => 'Croatia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '381' => Array(
            'name' => 'Yugoslavia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(230),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '380' => Array(
            'name' => 'Ukraine',
            'cityCodeLength' => 4,
            'zeroHack' => true,
            'exceptions' => Array(44, 432, 1762, 562, 622, 412, 522, 564, 53615, 642, 322, 448, 629, 512, 482, 532, 3355, 1821, 403, 222, 1852, 356, 3371, 267, 3443, 1694, 1965, 3058, 1627, 3385, 3356, 2718, 3370, 3260, 3231, 2785, 309, 2857, 2957, 2911, 294, 1705, 3, 295, 3250, 3387, 2523, 3246, 2674, 1854, 3433, 1711, 251, 2958, 2477, 2984, 307, 542, 352, 572, 552, 382, 472, 462, 654),
            'exceptions_max' => 5,
            'exceptions_min' => 1
        ),
        '378' => Array(
            'name' => 'RepublicOfSanMarino',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '377' => Array(
            'name' => 'Monaco',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '376' => Array(
            'name' => 'Andorra',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '375' => Array(
            'name' => 'Belarus',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(17, 163, 162, 232, 222),
            'exceptions_max' => 3,
            'exceptions_min' => 2
        ),
        '374' => Array(
            'name' => 'Armenia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 460, 520, 4300, 680, 860, 830, 550, 490, 570),
            'exceptions_max' => 4,
            'exceptions_min' => 1
        ),
        '373' => Array(
            'name' => 'Moldova',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '372' => Array(
            'name' => 'Estonia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2, 7),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '371' => Array(
            'name' => 'Latvia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '370' => Array(
            'name' => 'Lithuania',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(5, 37, 46, 45, 41),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '359' => Array(
            'name' => 'Bulgaria',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(2, 56, 62, 94, 92, 52, 32, 76, 64, 84, 82, 44, 42, 38, 46, 5722, 73, 66, 58, 68, 34, 86, 54, 6071, 7443, 5152, 7112, 7128, 9744, 9527, 5731, 8141, 3041, 6514, 6151, 3071, 9131, 7142, 3145, 8362, 3751, 6191, 9171, 2031, 7181, 6141, 7133, 5561, 3542, 3151, 3561, 7481, 3181, 5514, 3134, 6161, 4761, 5751, 3051),
            'exceptions_max' => 4,
            'exceptions_min' => 1
        ),
        '358' => Array(
            'name' => 'Finland',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(6, 5, 2, 8, 9, 3),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '357' => Array(
            'name' => 'Cyprus',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2, 3, 91, 92, 93, 94, 95, 96, 98),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '356' => Array(
            'name' => 'Malta',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '355' => Array(
            'name' => 'Albania',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(65, 62, 52, 64, 82, 7426, 42, 63),
            'exceptions_max' => 4,
            'exceptions_min' => 2
        ),
        '354' => Array(
            'name' => 'Iceland',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '353' => Array(
            'name' => 'IrishRepublic',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 402, 507, 902, 905, 509, 502, 903, 506, 504, 404, 405),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '352' => Array(
            'name' => 'Luxembourg',
            'cityCodeLength' => 2,
            'zeroHack' => true,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '351' => Array(
            'name' => 'Azores',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 2, 96, 676, 765, 96765),
            'exceptions_max' => 5,
            'exceptions_min' => 1
        ),
        '350' => Array(
            'name' => 'Gibraltar',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '299' => Array(
            'name' => 'Greenland',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '298' => Array(
            'name' => 'FaeroeIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '297' => Array(
            'name' => 'Aruba',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '291' => Array(
            'name' => 'Eritrea',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '290' => Array(
            'name' => 'St.Helena',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '269' => Array(
            'name' => 'ComorosAndMayotteIsland',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '268' => Array(
            'name' => 'Swaziland',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '267' => Array(
            'name' => 'Botswana',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '266' => Array(
            'name' => 'Lesotho',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '265' => Array(
            'name' => 'Malawi',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '264' => Array(
            'name' => 'Namibia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(811, 812, 813),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '263' => Array(
            'name' => 'Zimbabwe',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(9, 4, 637, 718),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '262' => Array(
            'name' => 'ReunionIslands',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '261' => Array(
            'name' => 'Madagascar',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '260' => Array(
            'name' => 'Zambia',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(26),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '259' => Array(
            'name' => 'Zanzibar',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '258' => Array(
            'name' => 'Mozambique',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '257' => Array(
            'name' => 'Burundi',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '256' => Array(
            'name' => 'Uganda',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(481, 485, 493),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '255' => Array(
            'name' => 'Tanzania',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '254' => Array(
            'name' => 'Kenya',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(11, 2, 37),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '253' => Array(
            'name' => 'Djibouti',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '252' => Array(
            'name' => 'Somalia',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '251' => Array(
            'name' => 'Ethiopia',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '250' => Array(
            'name' => 'RwandeseRepublic',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '249' => Array(
            'name' => 'Sudan',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(21, 51, 41, 31, 61, 11),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '248' => Array(
            'name' => 'Seychelles',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '247' => Array(
            'name' => 'Ascension',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '246' => Array(
            'name' => 'DiegoGarcia',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '245' => Array(
            'name' => 'Guinea-Bissau',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '244' => Array(
            'name' => 'Angola',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(9),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '243' => Array(
            'name' => 'DemocraticRepublic(ex.Zaire)',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '242' => Array(
            'name' => 'Congo',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '241' => Array(
            'name' => 'GaboneseRepublic',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '240' => Array(
            'name' => 'EquatorialGuinea',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '239' => Array(
            'name' => 'SaoTome-e-Principe',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '238' => Array(
            'name' => 'CapeVerde',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '237' => Array(
            'name' => 'Cameroon',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '236' => Array(
            'name' => 'CentralAfricanRepublic',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '235' => Array(
            'name' => 'Chad',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '234' => Array(
            'name' => 'Nigeria',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '233' => Array(
            'name' => 'Ghana',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '232' => Array(
            'name' => 'SierraLeone',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '231' => Array(
            'name' => 'Liberia',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '230' => Array(
            'name' => 'Mauritius',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '229' => Array(
            'name' => 'Benin',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '228' => Array(
            'name' => 'Togolese',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '227' => Array(
            'name' => 'Niger',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '226' => Array(
            'name' => 'BurkinaFaso',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '225' => Array(
            'name' => 'Ivory',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '224' => Array(
            'name' => 'Guinea',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(4),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '223' => Array(
            'name' => 'Mali',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '222' => Array(
            'name' => 'Mauritania',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '221' => Array(
            'name' => 'Senegal',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(63, 64, 67, 68, 82, 83, 84, 85, 86, 87, 90, 93, 94, 95, 96, 97, 98, 99),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '220' => Array(
            'name' => 'Gambia',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '218' => Array(
            'name' => 'Libya',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '216' => Array(
            'name' => 'Tunisia',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '212' => Array(
            'name' => 'Morocco',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '98' => Array(
            'name' => 'Iran',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(61, 11, 31, 51, 41, 21, 81, 71),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '95' => Array(
            'name' => 'Myanmar',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '94' => Array(
            'name' => 'SriLanka',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 9, 8),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '93' => Array(
            'name' => 'Afganistan',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '92' => Array(
            'name' => 'Pakistan',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(8288, 4521, 4331, 51, 21, 42, 61, 91, 71),
            'exceptions_max' => 4,
            'exceptions_min' => 2
        ),
        '91' => Array(
            'name' => 'India',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(11, 22, 33, 44, 40),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '90' => Array(
            'name' => 'Turkey',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '86' => Array(
            'name' => 'China',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(20, 29, 10, 22, 27, 28, 21, 24, 1350, 1351, 1352, 1353, 1354, 1355, 1356, 1357, 1358, 1359, 1360, 1361, 1362, 1363, 1364, 1365, 1366, 1367, 1368, 1369, 1370, 1371, 1372, 1373, 1374, 1375, 1376, 1377, 1378, 1379, 1380, 1381, 1382, 1383, 1384, 1385, 1386, 1387, 1388, 1389, 1390),
            'exceptions_max' => 4,
            'exceptions_min' => 2
        ),
        '84' => Array(
            'name' => 'Vietnam',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(511, 350, 4, 8),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '82' => Array(
            'name' => 'Korea,Republic',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(32, 62, 51, 2, 53, 42, 64, 16, 17, 18, 19),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '81' => Array(
            'name' => 'Japan',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(78, 45, 44, 75, 93, 52, 25, 6, 11, 22, 54, 3, 48, 92, 53, 82, 1070, 3070, 4070),
            'exceptions_max' => 4,
            'exceptions_min' => 1
        ),
        '66' => Array(
            'name' => 'Thailand',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '65' => Array(
            'name' => 'Singapore',
            'cityCodeLength' => 0,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '64' => Array(
            'name' => 'NewZealand',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(20, 21, 25, 26, 29),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '63' => Array(
            'name' => 'Philippines',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(455, 4661, 2150, 2155, 452, 2),
            'exceptions_max' => 4,
            'exceptions_min' => 1
        ),
        '62' => Array(
            'name' => 'Indonesia',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(22, 61, 21, 33, 36, 39, 35, 34, 24, 31, 81, 82),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '61' => Array(
            'name' => 'Australia',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(14, 15, 16, 17, 18, 19, 41),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '60' => Array(
            'name' => 'Malaysia',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(86, 88, 82, 85, 10, 18),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '58' => Array(
            'name' => 'Venezuela',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '57' => Array(
            'name' => 'Colombia',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 5, 7, 2, 4, 816),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '56' => Array(
            'name' => 'Chile',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '55' => Array(
            'name' => 'Brazil',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(243, 187, 485, 186, 246, 533, 173, 142, 473, 125, 495, 138, 482, 424, 192, 247, 484, 144, 442, 532, 242, 245, 194, 182, 123, 474, 486),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '54' => Array(
            'name' => 'Argentina',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(291, 11, 297, 223, 261, 299, 358, 341, 387, 381, 342),
            'exceptions_max' => 3,
            'exceptions_min' => 2
        ),
        '53' => Array(
            'name' => 'Cuba',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(680, 5, 8, 7, 686, 322, 419, 433, 335, 422, 692, 516, 226),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '52' => Array(
            'name' => 'Mexico',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(473, 181, 981, 112, 331, 5, 8, 951, 771, 492, 131, 246, 961, 459, 747),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '51' => Array(
            'name' => 'Peru',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 194, 198, 193, 190, 1877, 1878, 1879),
            'exceptions_max' => 4,
            'exceptions_min' => 1
        ),
        '49' => Array(
            'name' => 'Germany',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(651, 241, 711, 981, 821, 30, 971, 671, 921, 951, 521, 228, 234, 531, 421, 471, 961, 281, 611, 365, 40, 511, 209, 551, 641, 34202, 340, 351, 991, 771, 906, 231, 203, 211, 271, 911, 212, 841, 631, 721, 561, 221, 831, 261, 341, 871, 491, 591, 451, 621, 391, 291, 89, 395, 5021, 571, 441, 781, 208, 541, 69, 331, 851, 34901, 381, 33638, 751, 681, 861, 581, 731, 335, 741, 461, 761, 661, 345, 481, 34203, 375, 385, 34204, 361, 201, 33608, 161, 171, 172, 173, 177, 178, 179),
            'exceptions_max' => 5,
            'exceptions_min' => 2
        ),
        '48' => Array(
            'name' => 'Poland',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(192, 795, 862, 131, 135, 836, 115, 604, 641, 417, 601, 602, 603, 605, 606, 501, 885),
            'exceptions_max' => 3,
            'exceptions_min' => 3
        ),
        '47' => Array(
            'name' => 'Norway',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(43, 83, 62),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '46' => Array(
            'name' => 'Sweden',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(33, 21, 31, 54, 44, 13, 46, 40, 19, 63, 8, 60, 90, 18, 42),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '45' => Array(
            'name' => 'Denmark',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(9, 6, 7, 8, 1, 5, 3, 4, 251, 243, 249, 276, 70777, 80827, 90107, 90207, 90417, 90517),
            'exceptions_max' => 5,
            'exceptions_min' => 1
        ),
        '44' => Array(
            'name' => 'UnitedKingdom',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(21, 91, 44, 41, 51, 61, 31, 121, 117, 141, 185674, 18383, 15932, 116, 151, 113, 171, 181, 161, 207, 208, 158681, 115, 191, 177681, 114, 131, 18645),
            'exceptions_max' => 6,
            'exceptions_min' => 2
        ),
        '43' => Array(
            'name' => 'Austria',
            'cityCodeLength' => 4,
            'zeroHack' => false,
            'exceptions' => Array(1, 662, 732, 316, 512, 463),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '41' => Array(
            'name' => 'Switzerland',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '40' => Array(
            'name' => 'Romania',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1, 941, 916, 981),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '39' => Array(
            'name' => 'Italy',
            'cityCodeLength' => 3,
            'zeroHack' => true,
            'exceptions' => Array(71, 80, 35, 51, 30, 15, 41, 45, 33, 70, 74, 95, 31, 90, 2, 59, 39, 81, 49, 75, 85, 50, 6, 19, 79, 55, 330, 333, 335, 339, 360, 347, 348, 349),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '36' => Array(
            'name' => 'Hungary',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(1),
            'exceptions_max' => 1,
            'exceptions_min' => 1
        ),
        '34' => Array(
            'name' => 'Spain',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(4, 6, 3, 5, 96, 93, 94, 91, 95, 98),
            'exceptions_max' => 2,
            'exceptions_min' => 1
        ),
        '33' => Array(
            'name' => 'France',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(32, 14, 38, 59, 55, 88, 96, 28, 97, 42, 61),
            'exceptions_max' => 2,
            'exceptions_min' => 2
        ),
        '32' => Array(
            'name' => 'Belgium',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2, 9, 7, 3, 476, 477, 478, 495, 496),
            'exceptions_max' => 3,
            'exceptions_min' => 1
        ),
        '31' => Array(
            'name' => 'Netherlands',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(4160, 2268, 2208, 5253, 78, 72, 33, 20, 55, 26, 35, 74, 76, 40, 77, 10, 70, 75, 73, 38, 50, 15, 30, 58, 43, 24, 46, 13, 23, 45, 53, 61, 62, 65),
            'exceptions_max' => 4,
            'exceptions_min' => 2
        ),
        '30' => Array(
            'name' => 'Greece',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(1, 41, 81, 51, 61, 31, 71, 93, 94, 95, 97556, 97557),
            'exceptions_max' => 5,
            'exceptions_min' => 1
        ),
        '27' => Array(
            'name' => 'SouthAfrica',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(149, 1782, 1773, 444),
            'exceptions_max' => 4,
            'exceptions_min' => 3
        ),
        '21' => Array(
            'name' => 'Algeria',
            'cityCodeLength' => 1,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        ),
        '20' => Array(
            'name' => 'Egypt',
            'cityCodeLength' => 2,
            'zeroHack' => false,
            'exceptions' => Array(2, 3, 1221),
            'exceptions_max' => 4,
            'exceptions_min' => 1
        ),
        '8' => Array(
            'name' => 'Russia',
            'cityCodeLength' => 5,
            'zeroHack' => false,
            'exceptions' => Array(4162, 416332, 8512, 851111, 4722, 4725, 391379, 8442, 4732, 4152, 4154451, 4154459, 4154455, 41544513, 8142, 8332, 8612, 8622, 3525, 812, 8342, 8152, 3812, 4862, 3422, 342633, 8112, 9142, 8452, 3432, 3434, 3435, 4812, 3919, 8432, 8439, 3822, 4872, 3412, 3511, 3512, 3022, 4112, 4852, 4855, 3852, 3854, 8182, 818, 90, 3472, 4741, 4764, 4832, 4922, 8172, 8202, 8722, 4932, 493, 3952, 3951, 3953, 411533, 4842, 3842, 3843, 8212, 4942, 3912, 4712, 4742, 8362, 495, 499, 4966, 4964, 4967, 498, 8312, 8313, 3832, 383612, 3532, 8412, 4232, 423370, 423630, 8632, 8642, 8482, 4242, 8672, 8652, 4752, 4822, 482502, 4826300, 3452, 8422, 4212, 3466, 3462, 8712, 8352, 997, 901, 902, 903, 904, 905, 906, 908, 909, 910, 911, 912, 913, 914, 915, 916, 917, 918, 919, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 931, 932, 933, 934, 936, 937, 938, 950, 951, 952, 953, 960, 961, 962, 963, 964, 965, 967, 968, 980, 981, 982, 983, 984, 985, 987, 988, 989),
            'exceptions_max' => 8,
            'exceptions_min' => 2
        ),
        '7' => Array(
            'name' => 'Russia',
            'cityCodeLength' => 5,
            'zeroHack' => false,
            'exceptions' => Array(4162, 416332, 8512, 851111, 4722, 4725, 391379, 8442, 4732, 4152, 4154451, 4154459, 4154455, 41544513, 8142, 8332, 8612, 8622, 3525, 812, 8342, 8152, 3812, 4862, 3422, 342633, 8112, 9142, 8452, 3432, 3434, 3435, 4812, 3919, 8432, 8439, 3822, 4872, 3412, 3511, 3512, 3022, 4112, 4852, 4855, 3852, 3854, 8182, 818, 90, 3472, 4741, 4764, 4832, 4922, 8172, 8202, 8722, 4932, 493, 3952, 3951, 3953, 411533, 4842, 3842, 3843, 8212, 4942, 3912, 4712, 4742, 8362, 495, 499, 4966, 4964, 4967, 498, 8312, 8313, 3832, 383612, 3532, 8412, 4232, 423370, 423630, 8632, 8642, 8482, 4242, 8672, 8652, 4752, 4822, 482502, 4826300, 3452, 8422, 4212, 3466, 3462, 8712, 8352, 997, 901, 902, 903, 904, 905, 906, 908, 909, 910, 911, 912, 913, 914, 915, 916, 917, 918, 919, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 931, 932, 933, 934, 936, 937, 938, 950, 951, 952, 953, 960, 961, 962, 963, 964, 965, 967, 968, 980, 981, 982, 983, 984, 985, 987, 988, 989),
            'exceptions_max' => 8,
            'exceptions_min' => 2
        ),
        '1' => Array(
            'name' => 'USA',
            'cityCodeLength' => 3,
            'zeroHack' => false,
            'exceptions' => Array(),
            'exceptions_max' => 0,
            'exceptions_min' => 0
        )
    );


    public function __invoke($phone = '', $convert = true, $trim = true)
    {
        $phoneCodes = $this->phoneCodes;
        if (empty($phone)) {
            return '';
        }
        // очистка от лишнего мусора с сохранением информации о "плюсе" в начале номера
        $phone = trim($phone);
        $plus = ($phone[0] == '+');
        $phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
        $OriginalPhone = $phone;

        // конвертируем буквенный номер в цифровой
        if ($convert == true && !is_numeric($phone)) {
            $replace = array('2' => array('a', 'b', 'c'),
                '3' => array('d', 'e', 'f'),
                '4' => array('g', 'h', 'i'),
                '5' => array('j', 'k', 'l'),
                '6' => array('m', 'n', 'o'),
                '7' => array('p', 'q', 'r', 's'),
                '8' => array('t', 'u', 'v'),
                '9' => array('w', 'x', 'y', 'z'));

            foreach ($replace as $digit => $letters) {
                $phone = str_ireplace($letters, $digit, $phone);
            }
        }

        // заменяем 00 в начале номера на +
        if (substr($phone, 0, 2) == "00") {
            $phone = substr($phone, 2, strlen($phone) - 2);
            $plus = true;
        }

        // если телефон длиннее 7 символов, начинаем поиск страны
        if (strlen($phone) > 7)
            foreach ($phoneCodes as $countryCode => $data) {
                $codeLen = strlen($countryCode);
                if (substr($phone, 0, $codeLen) == $countryCode) {
                    // как только страна обнаружена, урезаем телефон до уровня кода города
                    $phone = substr($phone, $codeLen, strlen($phone) - $codeLen);
                    $zero = false;
                    // проверяем на наличие нулей в коде города
                    if ($data['zeroHack'] && $phone[0] == '0') {
                        $zero = true;
                        $phone = substr($phone, 1, strlen($phone) - 1);
                    }

                    $cityCode = null;
                    // сначала сравниваем с городами-исключениями
                    if ($data['exceptions_max'] != 0)
                        for ($cityCodeLen = $data['exceptions_max']; $cityCodeLen >= $data['exceptions_min']; $cityCodeLen--)
                            if (in_array(intval(substr($phone, 0, $cityCodeLen)), $data['exceptions'])) {
                                $cityCode = ($zero ? "0" : "") . substr($phone, 0, $cityCodeLen);
                                $phone = substr($phone, $cityCodeLen, strlen($phone) - $cityCodeLen);
                                break;
                            }
                    // в случае неудачи с исключениями вырезаем код города в соответствии с длиной по умолчанию
                    if (is_null($cityCode)) {
                        $cityCode = substr($phone, 0, $data['cityCodeLength']);
                        $phone = substr($phone, $data['cityCodeLength'], strlen($phone) - $data['cityCodeLength']);
                    }
                    // возвращаем результат
                    return ($plus ? "+" : "") . $countryCode . ' (' . $cityCode . ') ' . $this->phoneBlocks($phone);
                }
            }
        // возвращаем результат без кода страны и города
        return ($plus ? "+" : "") . $this->phoneBlocks($phone);
    }

// функция превращает любое числов в строку формата XX-XX-... или XXX-XX-XX-... в зависимости от четности кол-ва цифр
    protected function phoneBlocks($number)
    {
        $add = '';
        if (strlen($number) % 2) {
            $add = $number[0];
            $number = substr($number, 1, strlen($number) - 1);
        }
        return $add . implode("-", str_split($number, 2));
    }
}