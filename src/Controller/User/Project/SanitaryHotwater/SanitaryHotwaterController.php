<?php

declare(strict_types=1);

namespace App\Controller\User\Project\SanitaryHotwater;

use App\Entity\Project\SanitaryHotwater;
use App\Form\Project\SanitaryHotwaterType;
use App\Repository\Project\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(name: 'sanitaryHotwater_')]
#[IsGranted('ROLE_USER')]
final class SanitaryHotwaterController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
        protected Security $security
    ) {
    }

    #[Route('/espace-client/crée/sanitaryHotwater/{idProject}', name: 'create')]
    public function createSanitaryHotwater(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if ($project->getSanitaryHotwater()) {
            $this->addFlash('warning', 'Donné deja valider veuillez modifier mainHeading');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $sanitaryHotwater = new SanitaryHotwater();
        $form = $this->createForm(SanitaryHotwaterType::class, $sanitaryHotwater)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sanitaryHotwater->setProject($project);
            $this->entityManager->persist($sanitaryHotwater);
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok create sanitaryHotwater');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/sanitaryHotwater/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/espace-client/edit/sanitaryHotwater/{idProject}', name: 'edit')]
    public function editSanitaryHotwater(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if (!$project->getSanitaryHotwater()) {
            $this->addFlash('warning', 'Donné pas valider veuillez crée carpentry');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $sanitaryHotwater = $project->getSanitaryHotwater();
        $form = $this->createForm(SanitaryHotwaterType::class, $sanitaryHotwater)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok edit sanitaryHotwater');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/sanitaryHotwater/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
