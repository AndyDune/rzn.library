<?php
/**
 * Computes the difference of arrays with additional index check.
 *
 * @package andydune/array-container
 * @link  https://github.com/AndyDune/ArrayContainer for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2019 Andrey Ryzhov
 */

namespace Rzn\Library\ArrayContainerAction;

class ComputeDifferenceOfArrays extends AbstractAction
{

    protected $keysToIgnore = [];

    /**
     * @param array ...$arrays
     * @return array
     */
    public function execute(...$arrays)
    {
        $arrayResult = $this->arrayContainer->toArray();

        $result = [];

        foreach ($arrays as $array) {
            if ($one = $this->diff($arrayResult, $array)) {
                $result = array_replace_recursive($result, $one);
            }
        }

        foreach ($arrays as $array) {
            if ($one = $this->diff($array, $arrayResult)) {
                $result = array_replace_recursive($result, $one);
            }
        }

        return $result;
    }

    /**
     * @param mixed ...$keys
     * @return ComputeDifferenceOfArrays
     */
    public function ignoreKeys(...$keys)
    {
        $this->keysToIgnore = [];
        foreach ($keys as $keyToIgnore) {
            if (is_array($keyToIgnore)) {
                $this->keysToIgnore = array_merge($this->keysToIgnore, $keyToIgnore);
                continue;
            }
            $this->keysToIgnore[] = $keyToIgnore;
        }
        return $this;
    }

    protected function diff($array1, $array2)
    {
        if (!is_array($array2)) {
            $array2 = [];
        }
        $arrayResult = [];
        foreach ($array1 as $key => $value) {
            if (in_array($key, $this->keysToIgnore)) {
                continue;
            }
            if (is_array($value)) {
                if (array_key_exists($key, $array2)) {
                    $value = $this->diff($value, $array2[$key]);
                } else {
                    $value = $this->diff($value, []);
                }

                if ($value) {
                    $arrayResult[$key] = $value;
                }
                continue;
            }

            if (!array_key_exists($key, $array2)) {
                $arrayResult[$key] = $value;
                continue;
            }

            if ($array2[$key] == $value) {
                continue;
            }
            $arrayResult[$key] = $value;
        }

        return $arrayResult;
    }
}