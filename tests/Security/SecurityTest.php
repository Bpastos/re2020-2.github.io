<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SecurityTest extends WebTestCase
{
    public function testRegisterWithLoggedAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user+1@email.com',
            'password' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $client->request(Request::METHOD_GET, $router->generate('security_register'));
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testLoginWithLoggedAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user+1@email.com',
            'password' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }
}
