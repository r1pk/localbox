<?php

namespace App\Model\FileLocation;

readonly class LocalFileLocation implements FileLocationInterface
{
    public function __construct(
        protected string $path,
    ) {}

    public function __toString(): string
    {
        return $this->path;
    }
}
