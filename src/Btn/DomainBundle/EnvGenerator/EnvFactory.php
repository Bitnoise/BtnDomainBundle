<?php

namespace Btn\DomainBundle\EnvGenerator;

use Diarmuidie\EnvPopulate\File\Factory\FileFactory;

class EnvFactory extends FileFactory
{
    public function create($filename)
    {
        return new Env($filename);
    }
}
