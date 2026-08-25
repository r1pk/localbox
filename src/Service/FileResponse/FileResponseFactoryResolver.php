<?php

namespace App\Service\FileResponse;

use App\Exception\UnsupportedFileLocationException;
use App\Model\FileLocation\FileLocationInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class FileResponseFactoryResolver
{
    /** @param iterable<FileResponseFactoryInterface> $factories */
    public function __construct(
        #[AutowireIterator(FileResponseFactoryInterface::class)]
        protected iterable $factories,
    ) {}

    /**
     * @throws UnsupportedFileLocationException
     */
    public function resolve(FileLocationInterface $location): FileResponseFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($location)) {
                return $factory;
            }
        }

        throw new UnsupportedFileLocationException(
            'Unsupported file location: ' . $location::class,
        );
    }
}
