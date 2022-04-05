<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FaqController extends AbstractController
{
    #[Route('/faq', name: 'faqPage')]
    public function home(): Response
    {
        return $this->render('faq/faq.html.twig');
    }
}