<?php

namespace App\Controller;

use App\Service\GroupTokenRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index')]
    public function index(GroupTokenRegistry $registry): Response
    {
        return $this->render('home/index.html.twig', [
            'group_token' => $registry->issueToken()
        ]);
    }
}
