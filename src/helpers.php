<?php

if (! function_exists('array_merge_recursive_distinct')) {
    /**
     * Recursively merge two config arrays - retain distinct values and overwrite existing values.
     * @see http://docs.php.net/manual/da/function.array-merge-recursive.php#92195
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) &&
                isset($merged[$key]) &&
                is_array($merged[$key])
            ) {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
