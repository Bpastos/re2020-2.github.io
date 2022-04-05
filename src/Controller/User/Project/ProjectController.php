<?php

declare(strict_types=1);

namespace App\Controller\User\Project;

use App\Entity\Project\Owner;
use App\Entity\Project\Project;
use App\Entity\User;
use App\Form\Project\OwnerType;
use App\Form\Project\ProjectType;
use App\Repository\Project\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(name: 'project_')]
#[IsGranted('ROLE_USER')]
final class ProjectController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected Security $security
    ) {
    }

    #[Route('/espace-client/cree-un-projet', name: 'create')]
    public function createProject(Request $request): Response
    {
        $project = new Project();
        $ownerProject = new Owner();
        $projectForm = $this->createForm(ProjectType::class, $project)->handleRequest($request);
        $ownerForm = $this->createForm(OwnerType::class, $ownerProject)->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $project->setUser($user);
            $project->setOwnerProject($ownerProject);
            $this->entityManager->persist($project);
            $this->entityManager->persist($ownerProject);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le project à été crée');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/create.html.twig', [
            'projectForm' => $projectForm->createView(),
            'ownerForm' => $ownerForm->createView(),
        ]);
    }

    #[Route('/espace-client/modifier-un-projet/{idProject}', name: 'edit')]
    public function editProject(int $idProject, Request $request): Response
    {
        /** @var Project $project */
        $project = $this->projectRepository->findOneById($idProject);
        $owner = $project->getOwnerProject();
        /* @phpstan-ignore-next-line */
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('security_login');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $projectForm = $this->createForm(ProjectType::class, $project)->handleRequest($request);
        $ownerForm = $this->createForm(OwnerType::class, $owner)->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Le project à été crée');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/create.html.twig', [
            'projectForm' => $projectForm->createView(),
            'ownerForm' => $ownerForm->createView(),
        ]);
    }

    #[Route('/espace-client/voir-mes-projets', name: 'showAll')]
    public function showAllProjects(): Response
    {
        $projects = $this->projectRepository->findByUser($this->getUser());

        return $this->render('user/project/showAll.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/espace-client/showleproject/{idProject}', name: 'show')]
    public function showProject(int $idProject): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        return $this->render('user/project/show.html.twig', [
            'project' => $project,
        ]);
    }
}
