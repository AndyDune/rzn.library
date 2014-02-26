<?php
/**
 * Copyright (c) 2013 Andrey Ryzhov.
 * All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     rzn.library
 * @author      Andrey Ryzhov <info@rznw.ru>
 * @copyright   2013 Andrey Ryzhov.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://rznw.ru
 */

namespace Rzn\Library\ServiceManager;


class LazyConfig implements InitializerInterface
{
    /**
     * @var \Rzn\Library\ServiceManager
     */
    protected $sm;

    protected $setters = array();

    /**
     * @param \Rzn\Library\ServiceManager $sm
     */
    public function __construct(\Rzn\Library\ServiceManager $sm)
    {
        $this->sm = $sm;
    }


    public function addSetter($class, $method, $params)
    {
        $this->setters[$class] = array('method' => $method, 'params' => $params);
        return $this;
    }

    public function initialize($object)
    {
        if (!is_object($object))
            return false;

        $class = get_class($object);
        if (!array_key_exists($class, $this->setters))
            return true;
        $method = $this->setters[$class]['method'];
        $params = $this->setters[$class]['params'];
        if (!is_array($params))
            $params = array($params);
        call_user_func_array(array($object, $method), $params);
    }


} 