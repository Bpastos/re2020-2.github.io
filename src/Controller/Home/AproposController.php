<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AproposController extends AbstractController
{
    #[Route('/apropos', name: 'aproposPage')]
    public function home(): Response
    {
        return $this->render('apropos/apropos.html.twig');
    }
}
