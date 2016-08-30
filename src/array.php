<?php

if (!function_exists('get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!array_key_exists_safe($segment, $array)) {
                return value($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }
}

if (!function_exists('set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }
            $array =& $array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }
}

if (!function_exists('head')) {
    /**
     * Get the first element of an array. Useful for method chaining.
     *
     * @param  array $array
     * @return mixed
     */
    function head($array)
    {
        return reset($array);
    }
}

if (!function_exists('last')) {
    /**
     * Get the last element from an array.
     *
     * @param  array $array
     * @return mixed
     */
    function last($array)
    {
        return end($array);
    }
}

if (!function_exists('array_has')) {
    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @return bool
     */
    function array_has($array, $key)
    {
        if (empty($array) || is_null($key)) {
            return false;
        }
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach (explode('.', $key) as $segment) {
            if (!array_key_exists_safe($segment, $array)) {
                return false;
            }
            $array = $array[$segment];
        }
        return true;
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!array_key_exists_safe($segment, $array)) {
                return value($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }
}

/**
 * Return an array with only integers value contained in the array passed
 * @param array $array
 * @return array
 **/
function CleanUpArrayOfInt($array)
{
    $result = array();
    if (isNullOrEmptyArray($array)) {
        return $result;
    }
    reset($array);
    while (list($key, $value) = each($array)) {
        if (isInteger($value)) {
            $result[] = $value;
        }
    }
    reset($array);

    return $result;
}

if (!function_exists('array_split_filter')) {

    /**
     * Returns an array with two elements.
     *
     * Iterates over each value in the array passing them to the callback function.
     * If the callback function returns true, the current value from array is returned in the first
     * element of result array. If not, it is return in the second element of result array.
     *
     * Array keys are preserved.
     *
     * @param array $array
     * @param callable $callback
     * @return array
     * @see https://github.com/spatie/array-functions/blob/master/src/array_functions.php
     */
    function array_split_filter(array $array, callable $callback)
    {
        $passesFilter = array_filter($array, $callback);
        $negatedCallback = function ($item) use ($callback) {
            return !$callback($item);
        };
        $doesNotPassFilter = array_filter($array, $negatedCallback);
        return [$passesFilter, $doesNotPassFilter];
    }
}

if (!function_exists('in_array_column')) {

    /**
     * Checks whether specific value exists in array of object.
     * For exampe, following code
     *  $exist = in_array_column([['id' => 1], ['id' => 2], ['id' => 3]], 3, 'id');
     * will produce 2
     * @author wapmorgan
     * @since 2015.05.19
     * @param array $haystack Source array
     * @param mixed $needle Needed value
     * @param string $column Column to perform search
     * @param bool $strict Should search be strict or not.
     * @return bool True if value exists in array, False otherwise.
     * @see modified from https://github.com/wapmorgan/php-functions-repository/blob/master/i/in_array_column.php
     */
    function in_array_column($haystack, $needle, $column, $strict = false)
    {
        foreach ($haystack as $k => $elem) {
            if ((!$strict && $elem[$column] == $needle) || ($strict && $elem[$column] === $needle)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('objectToArray')) {

    /**
     * Convert objecte to the array.
     *
     * @param $object
     *
     * @return array
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function objectToArray($object) : array
    {
        if (!is_object($object) && !is_array($object)) {
            return [];
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        return array_map('objectToArray', $object);
    }
}

if (!function_exists('arrayToObject')) {

    /**
     * Convert array to the object.
     *
     * @param array $array PHP array
     *
     * @return mixed
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function arrayToObject($array)
    {
        if (isNullOrEmptyArray($array)) {
            return $array;
        }

        $object = new \stdClass();
        foreach ($array as $name => $value) {
            $object->$name = arrayToObject($value);
        }
        return $object;
    }
}

if (!function_exists('arrayToString')) {

    /**
     * Convert Array to string
     * expected output: <key1>="value1" <key2>="value2".
     *
     * @param array $array array to convert to string
     *
     * @return string
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function arrayToString(array $array = []) : string
    {
        if (isNullOrEmptyArray($array)) {
            return '';
        }

        $string = '';
        foreach ($array as $key => $value) {
            $string .= $key . '="' . $value . '" ';
        }
        return rtrim($string, ' ');
    }
}

if (!function_exists('array_key_exists_safe')) {

    /**
     * Check if a key exists in array
     * @param array $array
     * @param string $key
     * @return bool
     */
    function array_key_exists_safe(array $array, string $key) : bool
    {
        if (isNullOrEmptyArray($array) || isNullOrEmpty($key)) {
            return false;
        }

        return array_key_exists($key, $array);
    }
}

if (!function_exists('isNullOrEmptyArray')) {

    /**
     * Check if array is null or empty.
     * @param $array
     * @return bool
     */
    function isNullOrEmptyArray($array):bool
    {
        return $array === null || !is_array($array) || count($array) < 1;
    }
}

if (!function_exists('isNullOrEmptyArrayKey')) {

    /**
     * Check if an array key not exits or exists and is null or empty.
     * @param $array
     * @param string $key
     * @param bool $withTrim if set to true (default) check if trim()!='' too.
     * @return bool
     */
    function isNullOrEmptyArrayKey(array $array, string $key, bool $withTrim = true):bool
    {
        return !array_key_exists_safe($array, $key) || $array[$key]===null || isNullOrEmpty($array[$key], $withTrim);
    }
}

if (!function_exists('isNotNullOrEmptyArray')) {

    /**
     * Check if array is not null and not empty.
     * @param $array
     * @return bool
     */
    function isNotNullOrEmptyArray($array):bool
    {
        return !isNullOrEmptyArray($array);
    }
}

if (!function_exists('isNotNullOrEmptyArrayKey')) {

    /**
     * Check if an array key exists and is not null and not empty.
     * @param $array
     * @param string $key
     * @param bool $withTrim if set to true (default) check if trim()!='' too.
     * @return bool
     */
    function isNotNullOrEmptyArrayKey(array $array, string $key, bool $withTrim = true):bool
    {
        return !isNullOrEmptyArrayKey($array, $key, $withTrim);
    }
}
