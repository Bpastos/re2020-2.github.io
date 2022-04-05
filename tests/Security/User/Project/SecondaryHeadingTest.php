<?php

namespace App\Tests\Security\User\Project;

use App\Entity\Project\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SecondaryHeadingTest extends WebTestCase
{
    public function testCreateProjectForSecondaryHeading(): void
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
            'project[company]' => 'secondaryHeadingcompany',
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

    public function testCreateProjectForSecondaryHeadingFailedData(): void
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
            'project[company]' => 'secondaryHeadingcompanyfaileddata',
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

    public function testCreateSecondaryHeading(): void
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
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('secondaryHeadingcompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('secondaryHeading_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('secondaryHeading_create');
        $form = $crawler->filter('form[name=secondary_heading]')->form([
            'secondary_heading[location]' => 'En volume chauffé',
            'secondary_heading[heatingAppliance]' => 'Radiateur',
            'secondary_heading[information]' => 'informationmainheading',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditSecondaryHeading(): void
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
        $project = $projectRepository->findOneByCompany('secondaryHeadingcompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('secondaryHeading_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('secondaryHeading_edit');
        $form = $crawler->filter('form[name=secondary_heading]')->form([
            'secondary_heading[location]' => 'En volume chauffé',
            'secondary_heading[heatingAppliance]' => 'Radiateur',
            'secondary_heading[information]' => 'informationmainheading edit',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testEditSecondaryHeadingFailedData(array $formData): void
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
        $project = $projectRepository->findOneByCompany('secondaryHeadingcompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('secondaryHeading_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('secondaryHeading_edit');
        $form = $crawler->filter('form[name=secondary_heading]')->form($formData);
        $client->submit($form);
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testCreateSecondaryHeadingFailedData(array $formData): void
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
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('secondaryHeadingcompanyfaileddata');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('secondaryHeading_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('secondaryHeading_create');
        $form = $crawler->filter('form[name=secondary_heading]')->form($formData);
        $client->submit($form);
    }

    public function provideFailedData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'secondary_heading[location]' => 'En volume chauffé',
                'secondary_heading[heatingAppliance]' => 'Radiateur',
                'secondary_heading[information]' => 'informationmainheading',
            ];

        yield 'information is empty' => [$baseData(['secondary_heading[information]' => ''])];
    }
}
