<?php

declare(strict_types=1);

namespace App\Controller\Thermician;

use App\Entity\Project\Project;
use App\Entity\Thermician\Document;
use App\Entity\Thermician\Remark;
use App\Entity\Thermician\Thermician;
use App\Entity\Thermician\Ticket;
use App\Form\Thermician\DocumentType;
use App\Form\Thermician\RemarkType;
use App\Repository\Project\ProjectRepository;
use App\Repository\Thermician\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(name: 'thermician_')]
final class TicketStatusController extends AbstractController
{
    public function __construct(protected ProjectRepository $projectRepository, protected EntityManagerInterface $entityManager, protected Security $security, protected DocumentRepository $documentRepository)
    {
    }

    #[Route('/thermician/projets/{idProject}/create/remark/ticket', name: 'create_remark_ticket')]
    public function createRemark(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('thermician_home');
        }
        /** @var Thermician $thermician */
        $thermician = $this->getUser();
        /** @var Ticket $ticket */
        $ticket = $project->getTicket();

        $access = $this->isGranted('CAN_EDIT', $ticket);
        if (false === $access) {
            $this->addFlash('warning', 'Ce ticket ne vous apartietn pas');

            return $this->redirectToRoute('thermician_home');
        }

        $remark = new Remark();
        $form = $this->createForm(RemarkType::class, $remark)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $remark->setProject($project);
            $remark->setThermician($thermician);
            $remark->setProject($project);
            $thermician->setActiveTicket(null);
            $ticket->setOldThermician($thermician);
            $project->setStatus(Project::STATUS_ERROR_INFORMATION);
            $this->entityManager->persist($remark);
            $this->entityManager->flush();
            $this->addFlash('success', "La remarque à été envoyer a l'utilisateur, le ticket à été mis en pause vous pouvez séléctionner un autre ticket");

            return $this->redirectToRoute('thermician_home');
        }

        return $this->render('thermician/ticket/status/remark.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/thermician/projets/{idProject}/send/document/ticket', name: 'send_document')]
    public function sendDocument(int $idProject, Request $request): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('thermician_home');
        }
        /** @var Ticket $ticket */
        $ticket = $project->getTicket();
        $access = $this->isGranted('CAN_EDIT', $ticket);
        if (false === $access) {
            $this->addFlash('warning', 'Ce ticket ne vous apartietn pas');

            return $this->redirectToRoute('thermician_home');
        }
        $form = $this->createForm(DocumentType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $certificates = $form->get('certificate')->getData();
            foreach ($certificates as $certificate) {
                $projectName = str_replace(' ', '', $project->getProjectName());
                $file = md5(uniqid()).$projectName.$project->getId().'.'.$certificate->guessExtension();
                $certificate->move(
                    $this->getParameter('document_directory'),
                    $file
                );
                $document = new Document();
                $document->setName($name);
                $document->setCertificate($file);
                $document->setTicket($ticket);
                $this->entityManager->persist($document);
                $this->entityManager->flush();
                $this->addFlash('success', 'le document à bien été ajouter');

                return $this->redirectToRoute('thermician_send_document', ['idProject' => $project->getId()]);
            }
        }

        return $this->render('thermician/ticket/document/send.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
            'project' => $project,
        ]);
    }

    #[Route('/thermician/projets/{idProject}/validation/ticket', name: 'validation_ticket')]
    public function validationTicket(int $idProject): Response
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('thermician_home');
        }
        /** @var \App\Entity\Thermician\Ticket $ticket */
        $ticket = $project->getTicket();
        $access = $this->isGranted('CAN_EDIT', $ticket);
        if (false === $access) {
            $this->addFlash('warning', 'Ce ticket ne vous appartient pas');

            return $this->redirectToRoute('thermician_home');
        }

        return $this->render('thermician/ticket/status/finish.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/thermician/projets/{idProject}/finish/ticket', name: 'finish_ticket')]
    public function finishTicket(int $idProject): RedirectResponse
    {
        $project = $this->projectRepository->findOneById($idProject);
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('thermician_home');
        }
        /** @var \App\Entity\Thermician\Ticket $ticket */
        $ticket = $project->getTicket();
        $access = $this->isGranted('CAN_EDIT', $ticket);
        if (false === $access) {
            $this->addFlash('warning', 'Ce ticket ne vous appartient pas');

            return $this->redirectToRoute('thermician_home');
        }
        $documents = $this->documentRepository->findOneByTicket($ticket);
        if (!$documents) {
            $this->addFlash('warning', "Vous n'avez pas ajouter de document vous ne pouvez pas finir le projet");

            return $this->redirectToRoute('thermician_send_document', ['idProject' => $project->getId()]);
        }
        $this->addFlash('success', 'Le ticket est cloturé et finis félicitation ');
        $ticket->setIsActive(false);
        $ticket->setActiveThermician(null);
        /** @var Thermician $thermician */
        $thermician = $this->getUser();
        $ticket->setFinishedThermician($thermician);
        $project->setStatus(Project::STATUS_FINISH);
        $this->entityManager->flush();

        return $this->redirectToRoute('thermician_home');
    }
}
