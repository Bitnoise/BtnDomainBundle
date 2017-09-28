<?php

namespace Btn\DomainBundle\EnvGenerator;

use Diarmuidie\EnvPopulate\File\Env as BaseEnv;

class Env extends BaseEnv
{
    public function formatValueForEnv($value)
    {
        if (is_bool($value)) {
            return ($value) ? 'true' : 'false';
        }

        if ($value === 'true' || $value === 'false') {
            return $value;
        }

        if (is_int($value)) {
            return $value;
        }

        if (empty($value)) {
            return '';
        }

        $singelAndDoubleQuotes = '"\'';

        return trim($value, $singelAndDoubleQuotes);
    }
}
