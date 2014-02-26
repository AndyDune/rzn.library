<?php
/**
 * Copyright (c) 2014 Andrey Ryzhov.
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
 * @subpackage  Format
 * @author      Andrey Ryzhov <info@rznw.ru>
 * @copyright   2014 Andrey Ryzhov.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://rznw.ru
 */

namespace Rzn\Library\Format;


trait ValueToBitrixBoolTrait {

    /**
     * Преобразует ряд значений в принятый битриксом набор ("Y", "N")
     *
     * @param bool $flag
     * @return string
     */
    public function formatValueToBitrixBool($flag = true)
    {
        if (is_bool($flag)) {
            if ($flag) {
                $value = 'Y';
            } else {
                $value = 'N';
            }
        }
        else if ($flag) {
            if (is_string($flag) and strtoupper(substr($flag, 0, 1)) == 'Y') {
                $value = 'Y';
            } else if (is_string($flag)) {
                $value = 'N';
            } else {
                $value = 'Y';
            }
        }
        else {
            $value = 'N';
        }
        return $value;
    }

} 