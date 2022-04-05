<?php

declare(strict_types=1);

namespace App\Controller\Thermician;

use App\Entity\Project\Project;
use App\Entity\Thermician\Remark;
use App\Entity\Thermician\Thermician;
use App\Entity\Thermician\Ticket;
use App\Repository\Project\ProjectRepository;
use App\Repository\Thermician\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'thermician_')]
final class TicketController extends AbstractController
{
    public function __construct(protected EntityManagerInterface $entityManager, protected ProjectRepository $projectRepository, protected TicketRepository $ticketRepository)
    {
    }

    #[Route('/thermician/accueil', name: 'home')]
    public function home(): Response
    {
        /** @var Thermician $thermician */
        $thermician = $this->getUser();
        $tickets = $this->ticketRepository->findByIsActive(true);
        $thermicianTicket = $this->ticketRepository->findOneByActiveThermician($thermician);
        /** @var Ticket $pendingTickets */
        $pendingTickets = $this->ticketRepository->findByOldThermician($thermician);
        if ($pendingTickets) {
            foreach ($pendingTickets as $pendingTicket) {
                /** @var Project $project */
                $project = $pendingTicket->getProject();
                $remarks = $project->getRemarks();
                /** @var Remark $remark */
                foreach ($remarks as $remark) {
                    if (false === $remark->getIsActive()) {
                        $priorityTickets[] = $remark->getProject()->getTicket();
                    }
                }
            }

            return $this->render('thermician/home.html.twig', [
                'tickets' => $tickets,
                'activeTicket' => $thermicianTicket,
                'priorityTickets' => $priorityTickets,
            ]);
        }

        return $this->render('thermician/home.html.twig', [
            'tickets' => $tickets,
            'activeTicket' => $thermicianTicket,
        ]);
    }

    #[Route('/thermician/projets/{idProject}', name: 'show_ticket')]
    public function showProject(int $idProject): Response
    {
        /** @var Project $project */
        $project = $this->projectRepository->findOneById($idProject);
        /* @phpstan-ignore-next-line */
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('thermician_home');
        }

        return $this->render('thermician/ticket/show_ticket.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/thermician/projets/{idProject}/prendre', name: 'take_ticket')]
    public function takeTicket(int $idProject): Response
    {
        /** @var Thermician $thermician */
        $thermician = $this->getUser();
        /** @var Project $project */
        $project = $this->projectRepository->findOneById($idProject);
        /* @phpstan-ignore-next-line */
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('thermician_home');
        }
        /** @var \App\Entity\Thermician\Ticket $ticket */
        $ticket = $project->getTicket();
        if ($ticket->getActiveThermician()) {
            $this->addFlash('warning', 'ce ticket est déjà pris');

            return $this->redirectToRoute('thermician_home');
        }
        if ($ticket->getProject()->getRemarks()) {
            foreach ($ticket->getProject()->getRemarks() as $remark) {
                if (true === $remark->getIsActive()) {
                    $this->addFlash('warning', 'Ce ticket a une remark');

                    return $this->redirectToRoute('thermician_home');
                }
            }
        }
        if ($ticket->getOldThermician()) {
            $priority = $this->isGranted('IS_PRIORITY', $ticket);
            if (false === $priority) {
                $this->addFlash('warning', 'le ticket est prioriaitre a l ancien thermicien dessus');

                return $this->redirectToRoute('thermician_home');
            }
        }
        if ($thermician->getActiveTicket()) {
            $this->addFlash('warning', 'Vous avez déjà un ticket');

            return $this->redirectToRoute('thermician_home');
        }
        $ticket->setActiveThermician($thermician);
        $ticket->setIsActive(false);
        $this->entityManager->flush();
        $this->addFlash('success', 'Vous avez pris le ticket');

        return $this->redirectToRoute('thermician_show_my_ticket', [
            'idProject' => $project->getId(),
        ]);
    }

    #[Route('/thermician/projets/{idProject}/show/ticket', name: 'show_my_ticket')]
    public function showMyTicket(int $idProject): Response
    {
        /** @var Project $project */
        $project = $this->projectRepository->findOneById($idProject);
        /* @phpstan-ignore-next-line */
        if (!$project) {
            $this->addFlash('warning', "ce project n'existe pas");

            return $this->redirectToRoute('thermician_home');
        }
        /** @var Thermician $thermician */
        $thermician = $this->getUser();
        /** @var Ticket $ticket */
        $ticket = $project->getTicket();
        if ($ticket->getActiveThermician() !== $thermician) {
            $this->addFlash('warning', 'Ce ticket ne vous appartient pas ');

            return $this->redirectToRoute('thermician_home');
        }

        return $this->render('thermician/ticket/show_my_ticket.html.twig', [
            'project' => $project,
        ]);
    }
}
