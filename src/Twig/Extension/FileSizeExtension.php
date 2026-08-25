<?php

namespace App\Twig\Extension;

use App\Service\FileSizeFormatter;
use Twig\Attribute\AsTwigFilter;

class FileSizeExtension
{
    public function __construct(
        protected FileSizeFormatter $formatter,
    ) {}

    #[AsTwigFilter('format_file_size')]
    public function format(int $bytes): string
    {
        return $this->formatter->format($bytes);
    }
}
