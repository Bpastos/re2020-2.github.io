<?php

namespace App\Tests\Thermician;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ThermicianAuthenticationTest extends WebTestCase
{
    public function testLoginInThermicianLoginWithoutThermicianAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user+1@email.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        self::assertRouteSame('thermician_security_login');
    }

    public function testLoginThermicianWithThermicianAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('thermician_security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('thermician_home');
    }

    public function testLoginInUserLoginWithThermicianAccount(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'admin@test.com',
            'password' => '12',
        ]);

        $client->submit($form);
        self::assertRouteSame('security_login');
    }
}
