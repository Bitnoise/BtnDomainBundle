<?php

namespace Btn\DomainBundle\Util;

class ArrayUtil
{
    public static function getFlattened(array $array)
    {
        $flattened = [];
        foreach ($array as $element) {
            if (is_array($element)) {
                $flattened = array_merge($flattened, self::getFlattened($element));
            } else {
                $flattened[] = $element;
            }
        }

        return $flattened;
    }

    /**
     * Translate $array = [$key=>'xyz', $value=>'abc'] to $array = [$key => $value];
     * @param $array
     * @param $key
     * @param $value - if $value is null then get array row
     * @return array
     */
    public static function mapArray($array, $key, $value = null)
    {
        $results = [];
        foreach ($array as $v) {
            $results[$v[$key]] = !is_null($value) ? $v[$value] : $v;
        }

        return $results;
    }
}
