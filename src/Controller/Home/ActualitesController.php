<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ActualitesController extends AbstractController
{
    #[Route('/actualites', name: 'actualitesPage')]
    public function home(): Response
    {
        return $this->render('actualites/actualites.html.twig');
    }
}