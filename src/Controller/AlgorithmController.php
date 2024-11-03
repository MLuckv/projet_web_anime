<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AlgorithmController extends AbstractController
{
    #[Route('/algorithm', name: 'app_algorithm')]
    public function index(): Response
    {
        return $this->render('algorithm/index.html.twig', [
            'controller_name' => 'AlgorithmController',
        ]);
    }
}
