<?php

namespace App\Controller;

use App\Service\GroupTokenRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index')]
    public function index(GroupTokenRegistry $registry): Response
    {
        return $this->render('main/index.html.twig', [
            'group_token' => $registry->issueToken()
        ]);
    }
}
