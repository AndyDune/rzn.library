<?php
/**
 * ----------------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
 * | Сайт: www.rznw.ru                                 |
 * | Телефон: +7 (4912) 51-10-23                       |
 * | Дата: 13.08.2015
 * ----------------------------------------------------
 *
 */


namespace Rzn\Library\Waterfall\Test;

use Rzn\Library\ServiceManager\BitrixUserInterface;

class DropMain implements BitrixUserInterface
{
    /**
     * @var \CUser
     */
    protected $user;

    /**
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        // Базовый массив для
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
                                        'options' => []
                                    ],

                                ],
                                'stop' => false, // true для остановки из конфига
                            ],
                            'final' => [
                                'invokable' => 'Rzn\Library\Waterfall\Test\DropFinish',
                                'skip' => false, // true для пропуска
                            ],
                            'stop' => [
                                'invokable' => 'Rzn\Library\Waterfall\Test\DropStop',
                                'skip' => false, // true для пропуска
                            ],
                            'last' => [
                                'invokable' => 'Rzn\Library\Waterfall\Test\DropLast',
                            ],

                        ],
                        'error' => ['invokable' => 'Rzn\Library\Waterfall\Test\ResultError'],
                        'final' => ['invokable' => 'Rzn\Library\Waterfall\Test\ResultFinal'],
                        'stop' => ['invokable' => 'Rzn\Library\Waterfall\Test\ResultStop'],
                    ]
                ]
            ]
        ];


        $user = $this->getUser();
        if (is_object($user)) {
            pr('Нормально прошла инъекция через интерфейс');
        }
        pr('Дроп:' . $result->getCurrentFunction());
        // Проверить общий шар
        $result->addSharedResult('key', 'value');
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
        pr([$v1, $v2]);
    }

    public function setOneParam($v1)
    {
        pr($v1);
    }

}