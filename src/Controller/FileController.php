<?php

namespace App\Controller;

use App\Exception\FileNotFoundException;
use App\Repository\FileRepository;
use App\Service\FileResponse\FileResponseFactoryResolver;
use App\Service\FileStorage\FileStorageResolver;
use App\Service\UploadCoordinator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/f')]
final class FileController extends AbstractController
{
    #[Route('/upload', name: 'app_file_upload')]
    public function upload(Request $request, UploadCoordinator $coordinator): Response
    {
        $result = $coordinator->upload($request);

        if ($result->isComplete()) {
            $url = $this->generateUrl('app_file_group_show', [
                'token' => $result->getGroupToken()
            ]);

            return $this->json(['url' => $url], Response::HTTP_CREATED);
        }

        return $this->json([]);
    }

    #[Route('/{token}/download', name: 'app_file_download')]
    public function download(
        FileStorageResolver $fileStorageResolver,
        FileResponseFactoryResolver $fileResponseFactoryResolver,
        FileRepository $repository,
        string $token,
    ): Response
    {
        $file = $repository->findByToken($token);

        if ($file === null) {
            throw new FileNotFoundException('The requested file does not exist or has been removed');
        }

        $location = $fileStorageResolver->resolve()->locate($file);
        $factory = $fileResponseFactoryResolver->resolve($location);

        return $factory->create(
            $location,
            $file->getClientFilename(),
        );
    }
}
