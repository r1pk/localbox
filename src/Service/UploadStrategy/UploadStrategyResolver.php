<?php

namespace App\Service\UploadStrategy;

use App\Exception\MissingUploadStrategyException;
use App\Model\UploadRequest\UploadRequestInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class UploadStrategyResolver
{
    /** @param iterable<UploadStrategyInterface> $strategies */
    public function __construct(
        #[AutowireIterator(UploadStrategyInterface::class)]
        protected iterable $strategies,
    ) {}

    /**
     * @throws MissingUploadStrategyException
     */
    public function resolve(UploadRequestInterface $request): UploadStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($request)) {
                return $strategy;
            }
        }

        throw new MissingUploadStrategyException(
            'Unsupported upload request: ' . $request::class,
        );
    }
}
