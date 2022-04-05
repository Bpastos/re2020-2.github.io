<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'thermician_security_')]
final class ThermicianSecurityController extends AbstractController
{
    #[Route('/thermician/connexion', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes déjà connecter');

            return $this->redirectToRoute('homePage');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('thermician/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/thermician/deconnexion', name: 'logout')]
    public function logout(): RedirectResponse
    {
        $this->addFlash('success', 'Vous avez été déconnecter');

        return $this->redirectToRoute('homePage');
    }
}
