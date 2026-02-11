<?php

namespace App\Service;

class TemporaryDirectoryPathResolver
{
    public function resolve(): string
    {
        return sys_get_temp_dir();
    }
}
