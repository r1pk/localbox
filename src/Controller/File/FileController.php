<?php

namespace App\Controller\File;

use App\Exception\FileAvailabilityException;
use App\Repository\FileRepository;
use App\Service\Response\BinaryFileResponseFactory;
use App\Service\Upload\UploadCoordinator;
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
    public function download(BinaryFileResponseFactory $factory, FileRepository $repository, string $token): Response
    {
        $file = $repository->findByToken($token);

        if ($file === null) {
            throw new FileAvailabilityException('File does not exist');
        }

        return $factory->fromFile($file);
    }
}
