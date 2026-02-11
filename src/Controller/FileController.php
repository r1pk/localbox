<?php

namespace App\Controller;

use App\Exception\ApplicationException;
use App\Exception\FileAvailabilityException;
use App\Repository\FileRepository;
use App\Service\BinaryFileResponseFactory;
use App\Service\FileUploadCoordinator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/f')]
final class FileController extends AbstractController
{
    #[Route('/upload', name: 'app_file_upload')]
    public function upload(Request $request, FileUploadCoordinator $coordinator): Response
    {
        try {
            $result = $coordinator->upload($request);

            if ($result->isComplete()) {
                $url = $this->generateUrl('app_file_group_show', [
                    'token' => $result->getGroupToken()
                ]);

                return $this->json(['url' => $url], Response::HTTP_CREATED);
            }

            return $this->json([]);
        } catch (ApplicationException $exception) {
            return $this->json($exception->getResponsePayload(), $exception->getResponseStatus());
        } catch (Exception) {
            return $this->json(
                ['error' => 'An unexpected server error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    #[Route('/{token}/download', name: 'app_file_download')]
    public function download(BinaryFileResponseFactory $factory, FileRepository $repository, string $token): Response
    {
        try {
            $file = $repository->findByToken($token);

            if ($file === null) {
                throw new FileAvailabilityException('File does not exist');
            }

            return $factory->create($file);
        } catch (ApplicationException $exception) {
            return $this->json($exception->getResponsePayload(), $exception->getResponseStatus());
        } catch (Exception) {
            return $this->json(
                ['error' => 'An unexpected server error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
