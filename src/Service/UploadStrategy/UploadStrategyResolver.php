<?php

namespace App\Service\UploadStrategy;

use App\Exception\UnsupportedUploadRequestException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\Request;

class UploadStrategyResolver
{
    /** @param iterable<UploadStrategyInterface> $strategies */
    public function __construct(
        #[AutowireIterator(UploadStrategyInterface::class)]
        protected iterable $strategies,
    ) {}

    /**
     * @throws UnsupportedUploadRequestException
     */
    public function resolve(Request $request): UploadStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($request)) {
                return $strategy;
            }
        }

        throw new UnsupportedUploadRequestException('Unsupported upload request format');
    }
}
