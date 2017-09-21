<?php

namespace Btn\DomainBundle\Util;

class StringUtil
{
    public static function camelToUnderscore($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

    public static function getRelativeUrl($absoluteUrl)
    {
        if (!is_null($absoluteUrl)) {
            $url = parse_url(trim($absoluteUrl));
            if (isset($url['path']) && strlen($url['path']) > 0) {

                return ltrim($url['path'], '/');
            }
        }

        return null;
    }

    public static function sanitize($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }

    public static function vsprintf($string, array $args) {
        foreach($args as $arg => $value) {
            $string = str_replace('{' . $arg . '}', $value, $string);
        }

        return $string;
    }
}
