<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RegistrationController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserRepository $userRepository,
        protected UserPasswordHasherInterface $passwordHasher,
        protected MailerInterface $mailer
    ) {
    }

    #[Route('/inscription', name: 'security_register')]
    public function register(Request $request): Response
    {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes connecter, vous ne pouvez pas vous inscrire');

            return $this->redirectToRoute('homePage');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $checkEmail = $this->userRepository->findOneByEmail($user->getEmail());
            if (!$checkEmail) {
                $passwordHash = $this->passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($passwordHash);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $user->setEmailToken($this->generateToken());
                $this->entityManager->flush();
//                $email = (new TemplatedEmail())
//                    ->from('semihbasak25@gmail.com')
//                    ->to($user->getEmail())
//                    ->subject('Inscription réussite')
//                    ->htmlTemplate('mail/validation.html.twig')
//                    ->context(
//                        [
//                            'user' => $user,
//                            'token' => $user->getEmailToken(), ]
//                    );
//                $this->mailer->send($email);
                $this->addFlash('success', 'Votre compte à bien été crée');

                return $this->redirectToRoute('homePage');
            }
            $form = $this->createForm(UserType::class, $user)->handleRequest($request);
            $this->addFlash('warning', 'Cette adresse email est déjà utiliser');

            return $this->render('user/register.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/inscription/vérifier-mon-compte/{token}', name: 'security_valid')]
    public function verifyUser(string $token): RedirectResponse
    {
        $user = $this->userRepository->findOneByEmailToken($token);
        if (!$user) {
            $this->addFlash('warning', 'Erreur compte existe pas ou lien erreur');

            return $this->redirectToRoute('homePage');
        }
        if (true === $user->getIsVerified()) {
            $this->addFlash('warning', 'Compte déjà valide');

            return $this->redirectToRoute('homePage');
        }
        $user->setIsVerified(true);
        $user->setRoles(['ROLE_USER']);
        $this->entityManager->flush();
        $this->addFlash('warning', 'Votre compte à bien été valider');

        return $this->redirectToRoute('security_login');
    }

    private function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
