<?php

namespace App\Tests\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RegisterTest extends WebTestCase
{
    public function testSuccessFullRegistration(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_register'));

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'user@user.com',
            'user[firstName]' => 'John',
            'user[lastName]' => 'Doe',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testUserVerifyEmail(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $userRepository = $entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->findOneByEmail('user@user.com');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_valid', [
            'token' => $user->getEmailToken(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('security_login');
    }

    public function testSameEmailRegistration(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_register'));

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'user@user.com',
            'user[firstName]' => 'John 2',
            'user[lastName]' => 'Doe 2',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);
        $client->submit($form);
        self::assertRouteSame('security_register');
    }

    public function testSameEmailRegistrationCorrect(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_register'));

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'user@user.com',
            'user[firstName]' => 'John 2',
            'user[lastName]' => 'Doe 2',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);
        $client->submit($form);
        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'user@user2.com',
            'user[firstName]' => 'John 2',
            'user[lastName]' => 'Doe 2',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testNotSamePassword(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_register'));

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'user@user3.com',
            'user[firstName]' => 'John 3',
            'user[lastName]' => 'Doe 3',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password1',
        ]);
        $client->submit($form);
        self::assertRouteSame('security_register');
    }

    public function testNotSamePasswordCorrect(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_register'));

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'user@user3.com',
            'user[firstName]' => 'John 3',
            'user[lastName]' => 'Doe 3',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);
        $client->submit($form);
        self::assertRouteSame('security_register');
    }

    /**
     * @param array[email: string, password{first}: string, password{second}: string, firstName: string, lastName: string]$formData
     *
     * @dataProvider provideFailedRegisterData
     */
    public function testIfRegisterFailed(array $formData): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $client->request(Request::METHOD_GET, $router->generate('security_register'));

        $client->submitForm('Crée sont compte', $formData);

        self::assertRouteSame('security_register');
    }

    public function provideFailedRegisterData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'user[email]' => 'user4@email.com',
                'user[password][first]' => 'password',
                'user[password][second]' => 'password',
                'user[firstName]' => 'John 4',
                'user[lastName]' => 'Doe 4',
                ];

        yield 'email is empty' => [$baseData(['user[email]' => ''])];
        yield 'email is invalid' => [$baseData(['user[email]' => 'notaemail'])];
        yield 'email is not unique' => [$baseData(['user[email]' => 'user@user.com'])];
        yield 'password first is invalid' => [$baseData(['user[password][first]' => 'b'])];
        yield 'password second is invalid' => [$baseData(['user[password][second]' => 'a'])];
        yield 'password first is empty' => [$baseData(['user[password][first]' => ''])];
        yield 'password second is empty' => [$baseData(['user[password][second]' => ''])];
        yield 'firstName is not provide' => [$baseData(['user[firstName]' => ''])];
        yield 'lastName is not provide' => [$baseData(['user[lastName]' => ''])];
    }
}
