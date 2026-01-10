<?php

namespace App\Controller;

use App\Exception\ApplicationException;
use App\Exception\UploadTokenValidationException;
use App\Service\FileIngestor;
use App\Service\FilePersister;
use App\Service\GroupTokenRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class UploadController extends AbstractController
{
    #[Route('/', name: 'app_upload_index', methods: [Request::METHOD_GET])]
    public function index(GroupTokenRegistry $registry): Response
    {
        return $this->render('upload/index.html.twig', [
            'group_token' => $registry->issueToken()
        ]);
    }

    #[Route('/', name: 'app_upload_handle', methods: [Request::METHOD_POST])]
    public function handle(
        FileIngestor $ingestor,
        FilePersister $persister,
        GroupTokenRegistry $registry,
        Request $request,
    ): Response
    {
        try {
            $token = $request->request->get('group_token', '');

            if (!$registry->isTokenValid($token)) {
                throw new UploadTokenValidationException('Group token is invalid or expired');
            }

            $file = $ingestor->handle($request);

            if ($file !== null) {
                $entity = $persister->persist($file, $token);

                return $this->json(
                    [
                        'token' => $entity->getToken(),
                        'group_token' => $entity->getGroupToken(),
                    ],
                    Response::HTTP_CREATED,
                );
            }

            return $this->json([]);
        } catch (ApplicationException $exception) {
            return $this->json($exception->getResponsePayload(), $exception->getResponseStatus());
        } catch (Exception) {
            return $this->json(
                [
                    'error' => 'An unexpected server error occurred'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
