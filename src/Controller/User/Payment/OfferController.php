<?php

declare(strict_types=1);

namespace App\Controller\User\Payment;

use App\Repository\OfferRepository;
use App\Repository\Project\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'offer_')]
#[IsGranted('ROLE_USER')]
final class OfferController extends AbstractController
{
    #[Route('/espace-client/chosir-un-forfait/{idProject}', name: 'show')]
    public function showOffer(int $idProject, OfferRepository $offerRepository, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneById($idProject);
        $offers = $offerRepository->findAll();

        return $this->render('user/offer.html.twig', [
            'offers' => $offers,
            'project' => $project,
        ]);
    }
}
