<?php

declare(strict_types=1);

namespace App\Controller\User\Project\Comment;

use App\Entity\Project\Comment;
use App\Form\Project\CommentType;
use App\Repository\Project\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(name: 'comment_')]
#[IsGranted('ROLE_USER')]
final class CommentController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
        protected Security $security
    ) {
    }

    #[Route('/espace-client/crée/comment/{idProject}', name: 'create')]
    public function createComment(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if ($project->getComment()) {
            $this->addFlash('warning', 'Donné deja valider veuillez modifier Comment');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setProject($project);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok create comment');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/comment/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/espace-client/edit/comment/{idProject}', name: 'edit')]
    public function editComment(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if (!$project->getComment()) {
            $this->addFlash('warning', 'Donné pas valider veuillez crée Ventilation');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $comment = $project->getComment();
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok edit comment');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/comment/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
