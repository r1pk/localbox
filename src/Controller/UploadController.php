<?php

namespace App\Controller;

use App\Exception\FileUploadException;
use App\Service\FileIngestor;
use App\Service\FilePersister;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/')]
final class UploadController extends AbstractController
{
    const string SESSION_BATCH_UPLOAD_TOKEN_KEY = 'BATCH_UPLOAD_TOKEN';

    #[Route('/', name: 'app_upload_index', methods: [Request::METHOD_GET])]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $token = Uuid::v4()->toRfc4122();

        $session->set(self::SESSION_BATCH_UPLOAD_TOKEN_KEY, $token);

        return $this->render('upload/index.html.twig');
    }

    #[Route('/', name: 'app_upload_handle', methods: [Request::METHOD_POST])]
    public function handle(FileIngestor $ingestor, FilePersister $persister, Request $request): Response
    {
        try {
            $session = $request->getSession();
            $token = $session->get(self::SESSION_BATCH_UPLOAD_TOKEN_KEY);

            $file = $ingestor->handle($request);

            if ($file !== null) {
                $entity = $persister->persist($file, $token);

                return $this->json([], Response::HTTP_CREATED);
            }

            return $this->json([]);
        } catch (FileUploadException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
