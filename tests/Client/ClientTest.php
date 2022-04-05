<?php

namespace App\Tests\Client;

use App\Entity\Project\Project;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ClientTest extends WebTestCase
{
    public function testClientRegistration(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_register'));

        $form = $crawler->filter('form[name=user]')->form([
            'user[email]' => 'client@client.com',
            'user[firstName]' => 'Math',
            'user[lastName]' => 'Smith',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testClientVerifyEmail(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $userRepository = $entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->findOneByEmail('client@client.com');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_valid', [
            'token' => $user->getEmailToken(),
        ]));
        $client->followRedirect();
        self::assertRouteSame('security_login');
    }

    public function testClientLogin(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testClientCreateProject(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('project_create'));
        self::assertRouteSame('project_create');
        $form = $crawler->filter('form[name=owner]')->form([
            'owner[lastName]' => 'Fréderic',
            'owner[firstName]' => 'Jasme',
            'owner[address]' => '59 boulevard Michèle De Belfort',
            'owner[postalCode]' => '90000',
            'owner[city]' => 'Belfort',
            'project[projectName]' => 'Mon super projet',
            'project[firstName]' => 'Math',
            'project[lastName]' => 'Smith',
            'project[company]' => 'Math BTP Pro',
            'project[address]' => '20 rue du chemin de la pierre',
            'project[postalCode]' => '47500',
            'project[city]' => 'Lyon',
            'project[phoneNumber]' => '0765314520',
            'project[email]' => 'client@client.com',
            'project[masterJob]' => 'ARCHITECTE',
            'project[projectType]' => 'CONSTRUCTION',
            'project[cadastralReference]' => 'SDS653',
            'project[projectLocation]' => 'RASE CAMPAGNE',
            'project[constructionPlanDate][day]' => 01,
            'project[constructionPlanDate][month]' => 01,
            'project[constructionPlanDate][year]' => 2018,
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    private function createPdf(): UploadedFile
    {
        $fileName = 'foo.pdf';
        $filePath = sprintf('%s/foo.pdf', __DIR__);

        return new UploadedFile($filePath, $fileName, null, null, true);
    }

    public function testClientCreateBuilding(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('building_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('building_create');
        $form = $crawler->filter('form[name=building]')->form([
            'building[floorArea]' => 'floorArea',
            'building[livingArea]' => 'livingArea',
            'building[existingFloorArea]' => 'existingFloorArea',
            'building[lowFloor]' => 'lowFloor',
            'building[lowFloorThermal]' => 'Avec planelle',
            'building[highFloor]' => 'highFloor',
            'building[highFloorThermal]' => 'Avec planelle',
            'building[intermediateFloor]' => 'intermediateFloor',
            'building[intermediateFloorThermal]' => 'Avec planelle',
            'building[facades]' => 'facades',
            'building[particularWalls]' => 'particularWalls',
            'building[plan][0]' => $this->createPdf(),
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testClientCreateCarpentry(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
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

    public function testClientCreateComment(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('comment_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('comment_create');
        $form = $crawler->filter('form[name=comment]')->form([
            'comment[remark]' => 'Edit this',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testClientCreateMainHeading(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
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

    public function testClientCreateSanitaryHotWater(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
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

    public function testClientCreateSecondaryHeading(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
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

    public function testClientCreateVentilation(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('ventilation_create', [
            'idProject' => $project->getId(),
        ]));
        self::assertRouteSame('ventilation_create');
        $form = $crawler->filter('form[name=ventilation]')->form([
            'ventilation[systems]' => 'Double flux hydro',
            'ventilation[information]' => 'edit this',
        ]);
        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
    }

    public function testClientCreateBilling(): void
    {
        $client = static::createClient();
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));
        $form = $crawler->filter('form[name=login]')->form([
            'email' => 'client@client.com',
            'password' => 'password',
        ]);

        $client->submit($form);
        $client->followRedirect();
        self::assertRouteSame('homePage');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $projectRepository = $entityManager->getRepository(Project::class);
        /** @var \App\Entity\Project\Project $project */
        $project = $projectRepository->findOneByCompany('Math BTP Pro');
        $crawler = $client->request(Request::METHOD_GET, $router->generate('payment_create', [
            'idProject' => $project->getId(),
            'idOffer' => 1,
        ]));
        self::assertRouteSame('payment_create');
        $form = $crawler->filter('form[name=billing]')->form([
            'billing[firstName]' => 'Math',
            'billing[lastName]' => 'Smith',
            'billing[address]' => '59 boulevard Michèle De Belfort',
            'billing[postalCode]' => '90000',
            'billing[city]' => 'Belfort',
            'billing[phoneNumber]' => '0765314520',
        ]);
        $client->submit($form);
        $client->followRedirect();
    }
}
