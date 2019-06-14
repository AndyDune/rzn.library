<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 14.06.2019                            |
 * -----------------------------------------------
 *
 */

namespace Rzn\Library\Tests;
use PHPUnit_Framework_TestCase;
use Rzn\Library\ArrayContainer;
use Rzn\Library\ArrayContainerAction\ComputeDifferenceOfArrays;

class ArrayContainerTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobals = false;
    /**
     * @covers ComputeDifferenceOfArrays::diff
     * @covers ComputeDifferenceOfArrays::ignoreKeys
     */
    public function testComputeDifferenceOfArrays()
    {
        $array = [
            1,
            'two' => 2,
            3,
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction([0 => 11, 'two' => 22]);
        $this->assertEquals([1, 'two' => 2, 3], $result);
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction([0 => 1, 'two' => 2]);
        $this->assertEquals([1 => 3], $result);
        $array = [
            'r' => 'red',
            'rr' => [
                'r1' => 'red1',
                'rrr' => [
                    'r2' => 'red2',
                    'r22' => ['red22']
                ],
                'r2' => 'red2',
            ],
            'b' => 'blue'
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction([0 => 1, 'two' => 2]);
        $this->assertEquals($array, $result);
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(['rr' => []]);
        $this->assertEquals($array, $result);
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(['rr' => ['r5' => 'red1']]);
        $this->assertEquals($array, $result);
        $arrayWait = [
            'r' => 'red',
            'rr' => [
                'rrr' => [
                    'r2' => 'red2',
                    'r22' => ['red22']
                ],
                'r2' => 'red2',
            ],
            'b' => 'blue'
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(['rr' => ['r1' => 'red1']]);
        $this->assertEquals($arrayWait, $result);
        $arrayWait = [
            'r' => 'red',
            'rr' => [
                'rrr' => [
                    'r2' => 'red2',
                    'r22' => ['red22']
                ],
                'r2' => 'red2',
            ],
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(
            ['rr' => ['r1' => 'red1'], 'b' => 'blue']
        );
        $this->assertEquals($arrayWait, $result);
        $arrayWait = [
            'r' => 'red',
            'rr' => [
                'r1' => 'red1',
                'rrr' => [
                    'r22' => ['red22']
                ],
                'r2' => 'red2',
            ],
            'b' => 'blue'
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(
            ['rr' => ['rrr' => ['r2' => 'red2']]]
        );
        $this->assertEquals($arrayWait, $result);
        $arrayWait = [
            'r' => 'red',
            'rr' => [
                'r1' => 'red1',
                'rrr' => [
                    'r2' => 'red2',
                    'r22' => ['red22']
                ],
                'r2' => 'red2',
            ],
            'b' => 'blue'
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(
            ['rr' => ['rrr' => ['r22' => 'red22']]]
        );
        $this->assertEquals($arrayWait, $result);
        $arrayWait = [
            'r' => 'red',
            'rr' => [
                'r1' => 'red1',
                'rrr' => [
                    'r2' => 'red2'
                ],
                'r2' => 'red2',
            ],
            'b' => 'blue'
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction(new ComputeDifferenceOfArrays())->executeAction(
            ['rr' => ['rrr' => ['r22' => ['red22']]]]
        );
        $this->assertEquals($arrayWait, $result);
        $arrayWait = [
            'rr' => [
                'r1' => 'red1',
            ],
        ];
        $container = new ArrayContainer($array);
        $result = $container->setAction((new ComputeDifferenceOfArrays())->ignoreKeys('r', ['b', 'r2']))
            ->executeAction(
                ['rr' => ['rrr' => ['r22' => ['red22']]]]
            );
        $this->assertEquals($arrayWait, $result);
    }
}