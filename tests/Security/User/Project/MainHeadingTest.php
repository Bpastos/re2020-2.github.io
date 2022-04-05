<?php

namespace App\Tests\Security\User\Project;

use App\Entity\Project\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class MainHeadingTest extends WebTestCase
{
    public function testCreateProjectForMainHeading(): void
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
            'project[company]' => 'mainHeadingcompany',
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

    public function testCreateProjectForCreateMainHeadingFailedData(): void
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
            'project[company]' => 'mainHeadingcompanyfaileddata',
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

    public function testCreateMainHeading(): void
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
        $project = $projectRepository->findOneByCompany('mainHeadingcompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('mainHeading_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('mainHeading_create');
        $form = $crawler->filter('form[name=main_heading]')->form([
            'main_heading[systems]' => 'FIOUL',
            'main_heading[location]' => 'En volume chauffé',
            'main_heading[heatingAppliance]' => 'Radiateur',
            'main_heading[information]' => 'informationmainheading',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditMainHeading(): void
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
        $project = $projectRepository->findOneByCompany('mainHeadingcompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('mainHeading_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('mainHeading_edit');
        $form = $crawler->filter('form[name=main_heading]')->form([
            'main_heading[systems]' => 'FIOUL',
            'main_heading[location]' => 'En volume chauffé',
            'main_heading[heatingAppliance]' => 'Radiateur',
            'main_heading[information]' => 'informationmainheading edited',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testEditMainHeadingFailedData(array $formData): void
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
        $project = $projectRepository->findOneByCompany('mainHeadingcompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('mainHeading_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('mainHeading_edit');
        $form = $crawler->filter('form[name=main_heading]')->form($formData);
        $client->submit($form);
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testCreateMainHeadingFailedData(array $formData): void
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
        $project = $projectRepository->findOneByCompany('mainHeadingcompanyfaileddata');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('mainHeading_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('mainHeading_create');
        $form = $crawler->filter('form[name=main_heading]')->form($formData);
        $client->submit($form);
    }

    public function provideFailedData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'main_heading[systems]' => 'FIOUL',
                'main_heading[location]' => 'En volume chauffé',
                'main_heading[heatingAppliance]' => 'Radiateur',
                'main_heading[information]' => 'informationmainheading',
            ];

        yield 'information is empty' => [$baseData(['main_heading[information]' => ''])];
    }
}
