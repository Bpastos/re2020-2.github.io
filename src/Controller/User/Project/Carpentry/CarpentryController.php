<?php

declare(strict_types=1);

namespace App\Controller\User\Project\Carpentry;

use App\Entity\Project\Carpentry;
use App\Form\Project\CarpentryType;
use App\Repository\Project\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @SuppressWarnings
 */
#[Route(name: 'carpentry_')]
#[IsGranted('ROLE_USER')]
final class CarpentryController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
        protected Security $security
    ) {
    }

    #[Route('/espace-client/crée/carpentry/{idProject}', name: 'create')]
    public function createCarpentry(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if ($project->getCarpentry()) {
            $this->addFlash('warning', 'Donné deja valider veuillez modifier building');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $carpentry = new Carpentry();
        $form = $this->createForm(CarpentryType::class, $carpentry)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carpentry->setProject($project);
            $this->entityManager->persist($carpentry);
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok create carpentry');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/carpentry/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/espace-client/edit/carpentry/{idProject}', name: 'edit')]
    public function editCarpentry(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if (!$project->getCarpentry()) {
            $this->addFlash('warning', 'Donné pas valider veuillez crée carpentry');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $carpentry = $project->getCarpentry();
        $form = $this->createForm(CarpentryType::class, $carpentry)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok edit carpentry');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/carpentry/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
