<?php

namespace App\Service;

class FileSizeFormatter
{
    public function format(int $bytes): string
    {
        $i = floor(log($bytes) / log(1024));
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return sprintf('%.02F', $bytes / pow(1024, $i)) * 1 . ' ' . $units[$i];
    }
}
