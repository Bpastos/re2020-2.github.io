<?php

declare(strict_types=1);

namespace App\Controller\User\Project\SecondaryHeading;

use App\Entity\Project\SecondaryHeading;
use App\Form\Project\SecondaryHeadingType;
use App\Repository\Project\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(name: 'secondaryHeading_')]
#[IsGranted('ROLE_USER')]
final class SecondaryHeadingController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
        protected Security $security
    ) {
    }

    #[Route('/espace-client/crée/secondaryHeading/{idProject}', name: 'create')]
    public function createSecondaryHeading(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if ($project->getSecondaryHeading()) {
            $this->addFlash('warning', 'Donné deja valider veuillez modifier mainHeading');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $secondaryHeading = new SecondaryHeading();
        $form = $this->createForm(SecondaryHeadingType::class, $secondaryHeading)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $secondaryHeading->setProject($project);
            $this->entityManager->persist($secondaryHeading);
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok create secondaryHeading');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/secondaryHeading/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/espace-client/edit/secondaryHeading/{idProject}', name: 'edit')]
    public function editSecondaryHeading(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if (!$project->getSecondaryHeading()) {
            $this->addFlash('warning', 'Donné pas valider veuillez crée carpentry');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $secondaryHeading = $project->getSecondaryHeading();
        $form = $this->createForm(SecondaryHeadingType::class, $secondaryHeading)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok edit secondaryHeading');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/secondaryHeading/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
