<?php

declare(strict_types=1);

namespace App\Controller\User\Project\Building;

use App\Entity\Project\Building;
use App\Entity\Project\Plan;
use App\Form\Project\BuildingType;
use App\Repository\Project\PlanRepository;
use App\Repository\Project\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @SuppressWarnings
 */
#[Route(name: 'building_')]
#[IsGranted('ROLE_USER')]
final class BuildingController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
        protected Security $security,
        protected PlanRepository $planRepository
    ) {
    }

    #[Route('/espace-client/crée/{idProject}', name: 'create')]
    public function createBuilding(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if ($project->getBuilding()) {
            $this->addFlash('warning', 'Donné deja valider veuillez modifier building');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        $building = new Building();
        $form = $this->createForm(BuildingType::class, $building)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pdfs = $form->get('plan')->getData();
            /* @phpstan-ignore-next-line */
            foreach ($pdfs as $pdf) {
                $projectName = str_replace(' ', '', $project->getProjectName());
                /** @phpstan-ignore-next-line */
                $file = md5(uniqid()).$projectName.$project->getId().'.'.$pdf->guessExtension();
                /* @phpstan-ignore-next-line */
                $pdf->move(
                    $this->getParameter('pdf_directory'),
                    $file
                );
                $plan = new Plan();
                $plan->setName($file);
                $building->addPlan($plan);
                $this->entityManager->persist($plan);
            }
            $building->setProject($project);
            $this->entityManager->persist($building);
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok create building');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/building/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/espace-client/modifier/{idProject}', name: 'edit')]
    public function editBuilding(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "Ce projet n'existe pas");

            return $this->redirectToRoute('project_create');
        }
        if (!$project->getBuilding()) {
            $this->addFlash('warning', 'Donné inexistante');

            return $this->redirectToRoute('homePage');
        }
        $this->security->isGranted('IS_OWNER', $project);
        $this->denyAccessUnlessGranted('IS_OWNER', $project, 'Pas proprio');

        /** @var \App\Entity\Project\Building $building */
        $building = $project->getBuilding();
        $plans = null;
        /* @phpstan-ignore-next-line */
        if ($building->getPlan()) {
            $plans = $building->getPlan();
        }
        $form = $this->createForm(BuildingType::class, $building)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Ok edit building');

            return $this->redirectToRoute('homePage');
        }

        return $this->render('user/project/building/edit.html.twig', [
            'form' => $form->createView(),
            'plans' => $plans,
        ]);
    }

    #[Route('/espace-client/supprimer/plan/{namePlan}', name: 'planDelete')]
    public function deletePlan(string $namePlan): RedirectResponse
    {
        /** @var Plan $plan */
        $plan = $this->planRepository->findOneByName($namePlan);
        /** @var Building $building */
        $building = $plan->getBuilding();
        $project = $building->getProject();
        $this->entityManager->remove($plan);
        $this->entityManager->flush();
        $this->addFlash('success', 'Le plan à été supprimer du projet');

        return $this->redirectToRoute('building_edit', [
            'idProject' => $project->getId(),
        ]);
    }
}
