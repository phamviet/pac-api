<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class TokenController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function login(Request $request): Response
    {
        return $this->json([]);
    }
}
