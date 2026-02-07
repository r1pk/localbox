<?php

namespace App\Controller;

use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/g')]
final class FileGroupController extends AbstractController
{
    #[Route('/{token}', name: 'app_file_group_show')]
    public function show(FileRepository $repository, string $token): Response
    {
        $files = $repository->findByGroupToken($token);

        return $this->render('file_group/show.html.twig', [
            'files' => $files,
        ]);
    }
}
