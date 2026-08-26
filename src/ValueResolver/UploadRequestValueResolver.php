<?php

namespace App\ValueResolver;

use App\Exception\UnsupportedUploadRequestException;
use App\Model\UploadRequest\ChunkedUploadRequest;
use App\Model\UploadRequest\DirectUploadRequest;
use App\Model\UploadRequest\UploadRequestInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UploadRequestValueResolver implements ValueResolverInterface
{
    /**
     * @return iterable<UploadRequestInterface>
     *
     * @throws UnsupportedUploadRequestException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        if ($type === null || !is_a($type, UploadRequestInterface::class, true)) {
            return [];
        }

        $payload = $request->files->get('file');

        if (!$payload instanceof UploadedFile) {
            throw new UnsupportedUploadRequestException('Unsupported upload request format');
        }

        if ($request->request->has('dzuuid')) {
            return [
                new ChunkedUploadRequest(
                    uuid: $request->request->getString('dzuuid'),
                    chunkIndex: $request->request->getInt('dzchunkindex'),
                    totalChunkCount: $request->request->getInt('dztotalchunkcount'),
                    groupToken: $request->request->getString('group_token'),
                    payload: $payload,
                ),
            ];
        }

        return [
            new DirectUploadRequest(
                groupToken: $request->request->getString('group_token'),
                payload: $payload,
            ),
        ];
    }
}
