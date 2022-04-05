<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ConfidentController extends AbstractController
{
    #[Route('/confidentialite', name: 'confidentPage')]
    public function home(): Response
    {
        return $this->render('confidentialite/confidentialite.html.twig');
    }
}