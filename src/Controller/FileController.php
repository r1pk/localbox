<?php

namespace App\Controller;

use App\Exception\ApplicationException;
use App\Service\FileUploader;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(['/f', '/file'])]
final class FileController extends AbstractController
{
    #[Route(['/u', '/upload'], name: 'app_file_upload')]
    public function upload(Request $request, FileUploader $uploader): Response
    {
        try {
            $result = $uploader->upload($request);

            if ($result->isComplete()) {
                return $this->json(['url' => null], Response::HTTP_CREATED);
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
}
