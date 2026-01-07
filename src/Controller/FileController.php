<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/file')]
final class FileController extends AbstractController
{
    #[Route('/upload', name: 'app_file_upload')]
    public function upload(Request $request): Response
    {
        return $this->json([]);
    }
}
