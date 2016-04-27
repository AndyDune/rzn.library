<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 27.04.2016                                     |
 * ----------------------------------------------------
 *
 */


namespace Rzn\Library\Tests;
use Rzn\Library\Registry;
use PHPUnit_Framework_TestCase;
use Rzn\Library\Component\Helper\DeleteZerosAfterDotInNumber;

class HelperDeleteZerosAfterDotInNumberTest extends PHPUnit_Framework_TestCase
{
    public function testHelper()
    {
        $objectToTest = new DeleteZerosAfterDotInNumber();
        // 1.5000 -> 1.5
        $value = $objectToTest('1.5000');
        $this->assertEquals('1.5', $value);
        //1,5 -> 1.5
        $value = $objectToTest('1,5');
        $this->assertEquals('1.5', $value);

       //1 ,a 5 -> 1.5
        $value = $objectToTest('1 ,a 5');
        $this->assertEquals('1.5', $value);

       //.5000 -> 0.5
        $value = $objectToTest('.5000');
        $this->assertEquals('0.5', $value);

       //1.5000.30 -> 1.50003
        $value = $objectToTest('1.5000.30');
        $this->assertEquals('1.50003', $value);

        /** @var \Rzn\Library\Component\HelperManager $helperManager */
        $object = Registry::getServiceManager()->get('helper_manager')->get('deleteZerosAfterDotInNumber');
        $this->assertInstanceOf('Rzn\Library\Component\Helper\DeleteZerosAfterDotInNumber', $object);

    }
    
}