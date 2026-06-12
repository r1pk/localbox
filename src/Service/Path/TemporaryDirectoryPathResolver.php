<?php

namespace App\Service\Path;

class TemporaryDirectoryPathResolver
{
    public function resolve(): string
    {
        return sys_get_temp_dir();
    }
}
