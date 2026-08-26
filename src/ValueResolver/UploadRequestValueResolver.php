<?php

namespace App\ValueResolver;

use App\Model\UploadRequest\ChunkedUploadRequest;
use App\Model\UploadRequest\DirectUploadRequest;
use App\Model\UploadRequest\UploadRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UploadRequestValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() === null || !is_a($argument->getType(), UploadRequestInterface::class, true)) {
            return [];
        }

        if ($request->request->has('dzuuid') && $request->files->has('file')) {
            return [
                new ChunkedUploadRequest(
                    $request->request->getString('dzuuid'),
                    $request->request->getInt('dzchunkindex'),
                    $request->request->getInt('dztotalchunkcount'),
                    $request->request->getString('group_token'),
                    $request->files->get('file'),
                )
            ];
        }

        return [
            new DirectUploadRequest(
                $request->request->getString('group_token'), $request->files->get('file'),
            )
        ];
    }
}
