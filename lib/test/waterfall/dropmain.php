<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 13.08.2015
 * ----------------------------------------------------
 *
 *
 *
 *
 * тестовый скрипт
        $waterfall->getWaterfall('test', [
            'drops' => [
                'main' => [
                    'stop' => 0,
                    'skip' => 0
                ],
                'final' => [
                    'skip' => 0
                ],
                'stop' => [
                    'skip' => 0
                ],

            ]
        ])->execute();
*/

namespace Rzn\Library\Test\Waterfall;

use Rzn\Library\ServiceManager\BitrixUserInterface;
use Rzn\Library\InnerMessage\InnerMessagesAwareInterface;

class DropMain implements BitrixUserInterface
{
    /**
     * @var \CUser
     */
    protected $user;

    protected $im;

    protected $twoParams = null;

    protected $oneParam = null;

    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        // Пример массива для описания
        $array = [
            'waterfall' => [
                'streams' => [
                    'test' => [
                        'drops' => [
                            'main' => [
                                'invokable' => 'Rzn\Library\Waterfall\Test\DropMain',
                                'injector' => [
                                    'setParams' => [
                                        'handler' => 'setter',
                                        'options' => [
                                            'set' => 'params',
                                            'params' => ['v1', 'v2'],
                                            'method' => 'setTwoParams'
                                        ]
                                    ],
                                    'setParam' => [
                                        'handler' => 'setter',
                                        'options' => [
                                            'set' => 'params',
                                            'param' => ['vv1', 'vv2'],
                                            'method' => 'setOneParam'
                                        ]
                                    ],

                                    'interface' => [
                                        'handler' => 'initializer',
                                    ],

                                ],
                                'stop' => 0, // true для остановки из конфига
                            ],
                            'final' => [
                                'invokable' => 'Rzn\Library\Waterfall\Test\DropFinish',
                                'skip' => 0, // true для пропуска
                            ],
                            'stop' => [
                                'invokable' => 'Rzn\Library\Waterfall\Test\DropStop',
                                'skip' => 1, // true для пропуска
                            ],
                            'last' => [
                                'invokable' => 'Rzn\Library\Waterfall\Test\DropLast',
                                'injector' => [

                                    'setIM' => [
                                        'handler' => 'setter',
                                        'options' => [
                                            'set' => 'service',
                                            'service' => 'inner_messages',
                                            'method' => 'setInnerManager'
                                        ]
                                    ],
                                ],
                            ],

                        ],
                        'error' => ['invokable' => 'Rzn\Library\Waterfall\Test\ResultError'],
                        'final' => ['invokable' => 'Rzn\Library\Waterfall\Test\ResultFinal'],
                        'stop' => ['invokable' => 'Rzn\Library\Waterfall\Test\ResultStop'],
                        'route_select' => ['invokable' => 'Rzn\Library\Waterfall\Test\RouteSelect'],
                        'routes' => [
                            'skip_stop' => ['main', 'last']
                        ]
                    ]
                ]
            ]
        ];


        $result->addSharedResult('user', $this->getUser());

        $result->addSharedResult('two_params', $this->getTwoParams());
        $result->addSharedResult('one_param',  $this->getOneParam());

    }

    /**
     * Инъекция объекта битрикса о пользователе.
     *
     * @param \CUser $user
     * @return mixed
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Возврат объекта битрикса о пользователе.
     * @return \CUser
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setTwoParams($v1, $v2)
    {
        $this->twoParams = [$v1, $v2];
    }

    public function getTwoParams()
    {
        return $this->twoParams;
    }


    public function setOneParam($v1)
    {
        $this->oneParam = $v1;
    }

    public function getOneParam()
    {
        return $this->oneParam;
    }
}