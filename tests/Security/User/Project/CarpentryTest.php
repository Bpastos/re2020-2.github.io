<?php

namespace App\Tests\Security\User\Project;

use App\Entity\Project\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class CarpentryTest extends WebTestCase
{
    public function testCreateProjectForCarpentry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('project_create'));
        self::assertRouteSame('project_create');
        $form = $crawler->filter('form[name=owner]')->form([
            'owner[lastName]' => 'Carpentry',
            'owner[firstName]' => 'Carpentry',
            'owner[address]' => '21 rue Carpentry',
            'owner[postalCode]' => '25200',
            'owner[city]' => 'Paris',
            'project[projectName]' => 'Carpentry',
            'project[firstName]' => 'Carpentry',
            'project[lastName]' => 'Carpentry',
            'project[company]' => 'Carpentrycompany',
            'project[address]' => 'address',
            'project[postalCode]' => 'postalCode',
            'project[city]' => 'citycitycitycitycity',
            'project[phoneNumber]' => 'phoneNumber',
            'project[email]' => 'Carpentry@build.com',
            'project[masterJob]' => 'ARCHITECTE',
            'project[projectType]' => 'CONSTRUCTION',
            'project[cadastralReference]' => 'De 0 à 400m',
            'project[projectLocation]' => 'RASE CAMPAGNE',
            'project[constructionPlanDate][day]' => 01,
            'project[constructionPlanDate][month]' => 01,
            'project[constructionPlanDate][year]' => 2018,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateCarpentry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('Carpentrycompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('carpentry_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('carpentry_create');
        $form = $crawler->filter('form[name=carpentry]')->form([
            'carpentry[doors]' => 'doors',
            'carpentry[windows]' => 'windows',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateProjectForCarpentryForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('project_create'));
        self::assertRouteSame('project_create');
        $form = $crawler->filter('form[name=owner]')->form([
            'owner[lastName]' => 'Carpentryedit',
            'owner[firstName]' => 'Carpentryedit',
            'owner[address]' => '21 rue Carpentryedit',
            'owner[postalCode]' => '25200',
            'owner[city]' => 'Paris',
            'project[projectName]' => 'Carpentryedit',
            'project[firstName]' => 'Carpentryedit',
            'project[lastName]' => 'Carpentryedit',
            'project[company]' => 'Carpentryeditcomapny',
            'project[address]' => 'address',
            'project[postalCode]' => 'postalCode',
            'project[city]' => 'citycitycitycitycity',
            'project[phoneNumber]' => 'phoneNumber',
            'project[email]' => 'Carpentry@build.com',
            'project[masterJob]' => 'ARCHITECTE',
            'project[projectType]' => 'CONSTRUCTION',
            'project[cadastralReference]' => 'De 0 à 400m',
            'project[projectLocation]' => 'RASE CAMPAGNE',
            'project[constructionPlanDate][day]' => 01,
            'project[constructionPlanDate][month]' => 01,
            'project[constructionPlanDate][year]' => 2018,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateCarpentryForEdit(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('Carpentryeditcomapny');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('carpentry_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('carpentry_create');
        $form = $crawler->filter('form[name=carpentry]')->form([
            'carpentry[doors]' => 'edit this',
            'carpentry[windows]' => 'edit this',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditCarpentry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('Carpentryeditcomapny');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('carpentry_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('carpentry_edit');
        $form = $crawler->filter('form[name=carpentry]')->form([
            'carpentry[doors]' => 'edited',
            'carpentry[windows]' => 'edited',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function provideFailedData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'carpentry[doors]' => 'doorsdoors',
                'carpentry[windows]' => 'windowswindows',
            ];

        yield 'doorsdoors is empty' => [$baseData(['carpentry[doors]' => ''])];
        yield 'windows is empty' => [$baseData(['carpentry[windows]' => ''])];
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testEditCarpentryFailedData(array $formData): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('Carpentryeditcomapny');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('carpentry_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('carpentry_edit');
        $form = $crawler->filter('form[name=carpentry]')->form($formData);
        $client->submit($form);
    }

    public function testCreateProjectForCarpentryForCreateFailedData(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('project_create'));
        self::assertRouteSame('project_create');
        $form = $crawler->filter('form[name=owner]')->form([
            'owner[lastName]' => 'carpentryfaileddata',
            'owner[firstName]' => 'carpentryfaileddata',
            'owner[address]' => '21 rue carpentryfaileddata',
            'owner[postalCode]' => '25200',
            'owner[city]' => 'Paris',
            'project[projectName]' => 'carpentryfaileddata',
            'project[firstName]' => 'carpentryfaileddata',
            'project[lastName]' => 'carpentryfaileddata',
            'project[company]' => 'carpentryfaileddata',
            'project[address]' => 'address',
            'project[postalCode]' => 'postalCode',
            'project[city]' => 'citycitycitycitycity',
            'project[phoneNumber]' => 'phoneNumber',
            'project[email]' => 'Carpentry@build.com',
            'project[masterJob]' => 'ARCHITECTE',
            'project[projectType]' => 'CONSTRUCTION',
            'project[cadastralReference]' => 'De 0 à 400m',
            'project[projectLocation]' => 'RASE CAMPAGNE',
            'project[constructionPlanDate][day]' => 01,
            'project[constructionPlanDate][month]' => 01,
            'project[constructionPlanDate][year]' => 2018,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testCreateCarpentryFailedData(array $formData): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('carpentryfaileddata');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('carpentry_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('carpentry_create');
        $form = $crawler->filter('form[name=carpentry]')->form($formData);
        $client->submit($form);
    }
}
