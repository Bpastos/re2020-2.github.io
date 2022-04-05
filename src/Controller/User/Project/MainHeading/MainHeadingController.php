<?php

declare(strict_types=1);

namespace App\Controller\User\Project\MainHeading;

use App\Entity\Project\MainHeading;
use App\Form\Project\MainHeadingType;
use App\Repository\Project\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(name: 'mainHeading_')]
#[IsGranted('ROLE_USER')]
final class MainHeadingController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
        protected Security $security
    ) {
    }

    #[Route('/espace-client/crée/mainHeading/{idProject}', name: 'create')]
    public function createMainHeading(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if ($project->getMainHeading()) {
            $this->addFlash('warning', 'Donné deja valider veuillez modifier mainHeading');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $mainHeading = new MainHeading();
        $form = $this->createForm(MainHeadingType::class, $mainHeading)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mainHeading->setProject($project);
            $this->entityManager->persist($mainHeading);
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok create mainHeading');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/mainHeading/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/espace-client/edit/mainHeading/{idProject}', name: 'edit')]
    public function editMainHeading(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if (!$project->getMainHeading()) {
            $this->addFlash('warning', 'Donné pas valider veuillez crée carpentry');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $mainHeading = $project->getMainHeading();
        $form = $this->createForm(MainHeadingType::class, $mainHeading)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok edit mainHeading');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/mainHeading/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
