<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TarifsController extends AbstractController
{
    #[Route('/tarifs', name: 'tarifsPage')]
    public function home(): Response
    {
        return $this->render('tarifs/tarifs.html.twig');
    }
}