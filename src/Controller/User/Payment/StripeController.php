<?php

declare(strict_types=1);

namespace App\Controller\User\Payment;

use App\Entity\Billing;
use App\Entity\User;
use App\Form\BillingType;
use App\Repository\BillingRepository;
use App\Repository\OfferRepository;
use App\Repository\Project\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  @SuppressWarnings(PHPMD)
 */
#[Route(name: 'payment_')]
#[IsGranted('ROLE_USER')]
final class StripeController extends AbstractController
{
    protected string $publicKey;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ProjectRepository $projectRepository,
        protected OfferRepository $offerRepository,
        protected BillingRepository $billingRepository,
        string $publicKey,
    ) {
        $this->publicKey = $publicKey;
    }

    #[Route('/espace-client/facture/{idOffer}/{idProject}', name: 'create')]
    public function createPayment(int $idOffer, int $idProject, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $offer = $this->offerRepository->findOneById($idOffer);
        $project = $this->projectRepository->findOneById($idProject);

        $billing = new Billing();
        $billing
            ->setPrice($offer->getPrice())
            ->setName($offer->getName())
            ->setDescription($offer->getDescription())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName());

        $form = $this->createForm(BillingType::class, $billing)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $billing->setUser($user);
            $billing->setProject($project);
            $this->entityManager->persist($billing);
            $this->entityManager->flush();

            return $this->redirectToRoute('payment_final', [
                'idProject' => $idProject,
                'idBilling' => $billing->getId(),
            ]);
        }

        return $this->render('user/payment/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/espace-client/payer/project/{idBilling}/{idProject}', name: 'final')]
    public function stripePayment(int $idBilling, int $idProject)
    {
        $project = $this->projectRepository->findOneById($idProject);
        $billing = $this->billingRepository->findOneById($idBilling);

        $YOUR_DOMAIN = 'https://127.0.0.1:8000';
        $productStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $billing->getPrice(),
                'product_data' => [
                    'name' => $billing->getName(),
                    'images' => null,
                ],
            ],
            'quantity' => 1,
        ];
        Stripe::setApiKey($this->publicKey);
        $checkout_session = Session::create(
            [
            'line_items' => [[
                $productStripe,
            ]],
            'payment_method_types' => [
                'card',
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN.'/espace-client/projet/{CHECKOUT_SESSION_ID}/paiement/succes',
            'cancel_url' => $YOUR_DOMAIN.'/espace-client/projet/{CHECKOUT_SESSION_ID}/erreur',
             ]
        );
        $billing->setStripeSessionId($checkout_session->id);
        $this->entityManager->flush();

        return $this->redirect($checkout_session->url);
    }
}
