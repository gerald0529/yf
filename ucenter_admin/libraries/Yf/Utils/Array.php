<?php

class Yf_Utils_Array
{
	/**
	 * Returns the values of a specified column in an array.
	 * The input array should be multidimensional or an array of objects.
	 *
	 * For example,
	 *
	 * ```php
	 * $array = [
	 *     ['id' => '123', 'data' => 'abc'],
	 *     ['id' => '345', 'data' => 'def'],
	 * ];
	 * $result = ArrayHelper::getColumn($array, 'id');
	 * // the result is: ['123', '345']
	 *
	 * // using anonymous function
	 * $result = ArrayHelper::getColumn($array, function ($element) {
	 *     return $element['id'];
	 * });
	 * ```
	 *
	 * @param array $array
	 * @param string|\Closure $name
	 * @param boolean $keepKeys whether to maintain the array keys. If false, the resulting array
	 * will be re-indexed with integers.
	 * @return array the list of column values
	 */
	public static function getColumn($array, $column_name)
	{
		if(!function_exists("array_column"))
		{
			return array_map(function($element) use($column_name){return $element[$column_name];}, $array);
		}
		else
		{
			return array_column($array, $column_name);
		}
	}

	/**
	 * Builds a map (key-value pairs) from a multidimensional array or an array of objects.
	 * The `$from` and `$to` parameters specify the key names or property names to set up the map.
	 * Optionally, one can further group the map according to a grouping field `$group`.
	 *
	 * For example,
	 *
	 * ```php
	 * $array = [
	 *     ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
	 *     ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
	 *     ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
	 * ];
	 *
	 * $result = ArrayHelper::map($array, 'id', 'name');
	 * // the result is:
	 * // [
	 * //     '123' => 'aaa',
	 * //     '124' => 'bbb',
	 * //     '345' => 'ccc',
	 * // ]
	 *
	 * $result = ArrayHelper::map($array, 'id', 'name', 'class');
	 * // the result is:
	 * // [
	 * //     'x' => [
	 * //         '123' => 'aaa',
	 * //         '124' => 'bbb',
	 * //     ],
	 * //     'y' => [
	 * //         '345' => 'ccc',
	 * //     ],
	 * // ]
	 * ```
	 *
	 * @param array $array
	 * @param string|\Closure $from
	 * @param string|\Closure $to
	 * @param string|\Closure $group
	 * @return array
	 */
	public static function map($array, $from, $to, $group = null)
	{
		$result = [];
		foreach ($array as $element) {
			$key = $element[$from];
			$value = $element[$to];

			if ($group !== null) {
				$result[$element[$group]][$key] = $value;
			} else {
				$result[$key] = $value;
			}
		}

		return $result;
	}

	/**
	 * Returns a value indicating whether the given array is an associative array.
	 *
	 * An array is associative if all its keys are strings. If `$allStrings` is false,
	 * then an array will be treated as associative if at least one of its keys is a string.
	 *
	 * Note that an empty array will NOT be considered associative.
	 *
	 * @param array $array the array being checked
	 * @param boolean $allStrings whether the array keys must be all strings in order for
	 * the array to be treated as associative.
	 * @return boolean whether the array is associative
	 */
	public static function isAssociative($array, $allStrings = true)
	{
		if (!is_array($array) || empty($array)) {
			return false;
		}

		if ($allStrings) {
			foreach ($array as $key => $value) {
				if (!is_string($key)) {
					return false;
				}
			}
			return true;
		} else {
			foreach ($array as $key => $value) {
				if (is_string($key)) {
					return true;
				}
			}
			return false;
		}
	}

	/**
	 * Returns a value indicating whether the given array is an indexed array.
	 *
	 * An array is indexed if all its keys are integers. If `$consecutive` is true,
	 * then the array keys must be a consecutive sequence starting from 0.
	 *
	 * Note that an empty array will be considered indexed.
	 *
	 * @param array $array the array being checked
	 * @param boolean $consecutive whether the array keys must be a consecutive sequence
	 * in order for the array to be treated as indexed.
	 * @return boolean whether the array is associative
	 */
	public static function isIndexed($array, $consecutive = false)
	{
		if (!is_array($array)) {
			return false;
		}

		if (empty($array)) {
			return true;
		}

		if ($consecutive) {
			return array_keys($array) === range(0, count($array) - 1);
		} else {
			foreach ($array as $key => $value) {
				if (!is_int($key)) {
					return false;
				}
			}
			return true;
		}
	}

	/**
	 * Determines if an array is associative.
	 *
	 * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
	 *
	 * @param  array  $array
	 * @return bool
	 */
	public static function isAssoc(array $array)
	{
		$keys = array_keys($array);
		return array_keys($keys) !== $keys;
	}

	/**
	 * Recursively sort an array by keys and values.
	 *
	 * @param  array  $array
	 * @return array
	 */
	public static function sortRecursive($array)
	{
		foreach ($array as &$value) {
			if (is_array($value)) {
				$value = static::sortRecursive($value);
			}
		}
		if (static::isAssoc($array)) {
			ksort($array);
		} else {
			sort($array);
		}
		return $array;
	}

	/**
	 * Filter the array using the given callback.
	 *
	 * @param  array  $array
	 * @param  callable  $callback
	 * @return array
	 */
	public static function where($array, callable $callback)
	{
		return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
	}
}