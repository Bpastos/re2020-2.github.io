<?php

namespace App\Tests\Security\User\Project;

use App\Entity\Project\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class SanitaryHotwaterTest extends WebTestCase
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
            'project[company]' => 'sanitaryHotwatercompany',
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

    public function testCreateProjectForSecondaryHeadingForEdit(): void
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
            'project[company]' => 'editsanitaryHotwatercompany',
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

    public function testCreateSanitaryHotWater(): void
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
        $project = $projectRepository->findOneByCompany('sanitaryHotwatercompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('sanitaryHotwater_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('sanitaryHotwater_create');
        $form = $crawler->filter('form[name=sanitary_hotwater]')->form([
            'sanitary_hotwater[location]' => 'En volume chauffé',
            'sanitary_hotwater[thermodynamicDHW]' => 'texteeeeeeeee',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testCreateSanitaryHotWaterForEdit(): void
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
        $project = $projectRepository->findOneByCompany('editsanitaryHotwatercompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('sanitaryHotwater_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('sanitaryHotwater_create');
        $form = $crawler->filter('form[name=sanitary_hotwater]')->form([
            'sanitary_hotwater[location]' => 'En volume chauffé',
            'sanitary_hotwater[thermodynamicDHW]' => 'Edit this',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testEditSanitaryHotwater(): void
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
        $project = $projectRepository->findOneByCompany('editsanitaryHotwatercompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('sanitaryHotwater_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('sanitaryHotwater_edit');
        $form = $crawler->filter('form[name=sanitary_hotwater]')->form([
            'sanitary_hotwater[location]' => 'En volume chauffé',
            'sanitary_hotwater[thermodynamicDHW]' => 'edited',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testCreateSanitaryHotWaterFailedData(array $formData): void
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
        $project = $projectRepository->findOneByCompany('sdsdsdsdsd');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('sanitaryHotwater_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('sanitaryHotwater_create');
        $form = $crawler->filter('form[name=sanitary_hotwater]')->form($formData);
        $client->submit($form);
    }

    /**
     * @dataProvider provideFailedData
     */
    public function testEditSanitaryHotwaterFailedData(array $formData): void
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
        $project = $projectRepository->findOneByCompany('sanitaryHotwatercompany');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('sanitaryHotwater_edit', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('sanitaryHotwater_edit');
        $form = $crawler->filter('form[name=sanitary_hotwater]')->form($formData);
        $client->submit($form);
    }

    public function provideFailedData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'sanitary_hotwater[location]' => 'En volume chauffé',
                'sanitary_hotwater[thermodynamicDHW]' => 'thermodynamicDHW',
            ];

        yield 'thermodynamicDHW is empty' => [$baseData(['sanitary_hotwater[thermodynamicDHW]' => ''])];
    }
}
